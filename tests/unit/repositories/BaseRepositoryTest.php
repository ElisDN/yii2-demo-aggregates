<?php

namespace tests\unit\repositories;

use app\entities\Employee\Id;
use app\entities\Employee\Name;
use app\entities\Employee\Phone;
use app\entities\Employee\Status;
use app\repositories\EmployeeRepository;
use app\repositories\NotFoundException;
use tests\unit\entities\Employee\EmployeeBuilder;
use Codeception\Test\Unit;

abstract class BaseRepositoryTest extends Unit
{
    /**
     * @var EmployeeRepository
     */
    protected $repository;

    public function testGet(): void
    {
        $this->repository->add($employee = (new EmployeeBuilder())->build());

        $found = $this->repository->get($employee->getId());

        $this->assertNotNull($found);
        $this->assertEquals($employee->getId(), $found->getId());
    }

    public function testGetNotFound(): void
    {
        $this->expectException(NotFoundException::class);

        $this->repository->get(new Id(uniqid()));
    }

    public function testAdd(): void
    {
        $employee = (new EmployeeBuilder())
            ->withPhones([
                new Phone(7, '888', '00000001'),
                new Phone(7, '888', '00000002'),
            ])
            ->build();

        $this->repository->add($employee);

        $found = $this->repository->get($employee->getId());

        $this->assertEquals($employee->getId(), $found->getId());
        $this->assertEquals($employee->getName(), $found->getName());
        $this->assertEquals($employee->getAddress(), $found->getAddress());

        $this->assertEquals(
            $employee->getCreateDate()->getTimestamp(),
            $found->getCreateDate()->getTimestamp()
        );

        $this->checkPhones($employee->getPhones(), $found->getPhones());
        $this->checkStatuses($employee->getStatuses(), $found->getStatuses());
    }

    public function testSave(): void
    {
        $employee = (new EmployeeBuilder())
            ->withPhones([
                new Phone(7, '888', '00000001'),
                new Phone(7, '888', '00000002'),
            ])
            ->build();

        $this->repository->add($employee);

        $edit = $this->repository->get($employee->getId());

        $edit->rename($name = new Name('New', 'Test', 'Name'));
        $edit->addPhone($phone = new Phone(7, '888', '00000003'));
        $edit->archive(new \DateTimeImmutable());

        $this->repository->save($edit);

        $found = $this->repository->get($employee->getId());

        $this->assertTrue($found->isArchived());
        $this->assertEquals($name, $found->getName());

        $this->checkPhones($edit->getPhones(), $found->getPhones());
        $this->checkStatuses($edit->getStatuses(), $found->getStatuses());
    }

    public function testRemove(): void
    {
        $id = new Id(uniqid());
        $employee = (new EmployeeBuilder())->withId($id)->build();
        $this->repository->add($employee);

        $this->repository->remove($employee);

        $this->expectException(NotFoundException::class);

        $this->repository->get($id);
    }

    private function checkPhones(array $expected, array $actual): void
    {
        $phoneData = static function (Phone $phone) {
            return $phone->getFull();
        };

        $this->assertEquals(
            array_map($phoneData, $expected),
            array_map($phoneData, $actual)
        );
    }

    private function checkStatuses(array $expected, array $actual): void
    {
        $statusData = static function (Status $status) {
            return [
                'value' => $status->getValue(),
                'date' => $status->getDate()->getTimestamp(),
            ];
        };

        $this->assertEquals(
            array_map($statusData, $expected),
            array_map($statusData, $actual)
        );
    }
}
