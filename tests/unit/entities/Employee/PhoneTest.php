<?php

namespace tests\unit\entities\Employee;

use app\entities\Employee\Events\EmployeePhoneAdded;
use app\entities\Employee\Events\EmployeePhoneRemoved;
use app\entities\Employee\Phone;
use Codeception\Test\Unit;

class PhoneTest extends Unit
{
    public function testAdd(): void
    {
        $employee = (new EmployeeBuilder())->build();

        $employee->addPhone($phone = new Phone(7, '888', '00000001'));

        $this->assertNotEmpty($phones = $employee->getPhones());
        $this->assertEquals($phone, end($phones));

        $this->assertNotEmpty($events = $employee->releaseEvents());
        $this->assertInstanceOf(EmployeePhoneAdded::class, end($events));
    }

    public function testAddExists(): void
    {
        $employee = (new EmployeeBuilder())
            ->withPhones([$phone = new Phone(7, '888', '00000001')])
            ->build();

        $this->expectExceptionMessage('Phone already exists.');

        $employee->addPhone($phone);
    }

    public function testRemove(): void
    {
        $employee = (new EmployeeBuilder())
            ->withPhones([
                new Phone(7, '888', '00000001'),
                new Phone(7, '888', '00000002'),
            ])
            ->build();

        $this->assertCount(2, $employee->getPhones());

        $employee->removePhone(1);

        $this->assertCount(1, $employee->getPhones());

        $this->assertNotEmpty($events = $employee->releaseEvents());
        $this->assertInstanceOf(EmployeePhoneRemoved::class, end($events));
    }

    public function testRemoveNotExists(): void
    {
        $employee = (new EmployeeBuilder())->build();

        $this->expectExceptionMessage('Phone is not found.');

        $employee->removePhone(42);
    }

    public function testRemoveLast(): void
    {
        $employee = (new EmployeeBuilder())
            ->withPhones([
                new Phone(7, '888', '00000001'),
            ])
            ->build();

        $this->expectExceptionMessage('Cannot remove the last phone.');

        $employee->removePhone(0);
    }
}
