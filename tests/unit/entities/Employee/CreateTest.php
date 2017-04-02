<?php

namespace tests\unit\entities\Employee;

use app\entities\Employee\Address;
use app\entities\Employee\Employee;
use app\entities\Employee\Id;
use app\entities\Employee\Events\EmployeeCreated;
use app\entities\Employee\Name;
use app\entities\Employee\Phone;
use app\entities\Employee\Status;
use Codeception\Test\Unit;

class CreateTest extends Unit
{
    public function testSuccess(): void
    {
        $employee = new Employee(
            $id = Id::next(),
            $date = new \DateTimeImmutable(),
            $name = new Name('Пупкин', 'Василий', 'Петрович'),
            $address = new Address('Россия', 'Липецкая обл.', 'г. Пушкин', 'ул. Ленина', 25),
            $phones = [
                new Phone(7, '920', '00000001'),
                new Phone(7, '910', '00000002'),
            ]
        );

        $this->assertEquals($id, $employee->getId());
        $this->assertEquals($date, $employee->getCreateDate());
        $this->assertEquals($name, $employee->getName());
        $this->assertEquals($address, $employee->getAddress());
        $this->assertEquals($phones, $employee->getPhones());

        $this->assertNotNull($employee->getCreateDate());

        $this->assertTrue($employee->isActive());

        $this->assertCount(1, $statuses = $employee->getStatuses());
        $this->assertTrue(end($statuses)->isActive());

        $this->assertNotEmpty($events = $employee->releaseEvents());
        $this->assertInstanceOf(EmployeeCreated::class, end($events));
    }

    public function testWithoutPhones(): void
    {
        $this->expectExceptionMessage('Employee must contain at least one phone.');

        new Employee(
            Id::next(),
            new \DateTimeImmutable(),
            new Name('Пупкин', 'Василий', 'Петрович'),
            new Address('Россия', 'Липецкая обл.', 'г. Пушкин', 'ул. Ленина', 25),
            []
        );
    }

    public function testWithSamePhoneNumbers(): void
    {
        $this->expectExceptionMessage('Phone already exists.');

        new Employee(
            Id::next(),
            new \DateTimeImmutable(),
            new Name('Пупкин', 'Василий', 'Петрович'),
            new Address('Россия', 'Липецкая обл.', 'г. Пушкин', 'ул. Ленина', 25),
            [
                new Phone(7, '920', '00000001'),
                new Phone(7, '920', '00000001'),
            ]
        );
    }
}
