<?php

namespace tests\unit\entities\Employee;

use app\entities\Employee\Address;
use app\entities\Employee\Employee;
use app\entities\Employee\Id;
use app\entities\Employee\Name;
use app\entities\Employee\Phone;

class EmployeeBuilder
{
    private $id;
    private $date;
    private $name;
    private $address;
    private $phones = [];
    private $archived = false;

    public function __construct()
    {
        $this->id = Id::next();
        $this->date = new \DateTimeImmutable();
        $this->name = new Name('Пупкин', 'Василий', 'Петрович');
        $this->address = new Address('Россия', 'Липецкая обл.', 'г. Пушкин', 'ул. Ленина', 25);
        $this->phones[] = new Phone(7, '000', '00000000');
    }

    public function withId(Id $id): self
    {
        $clone = clone $this;
        $clone->id = $id;
        return $clone;
    }

    public function withPhones(array $phones): self
    {
        $clone = clone $this;
        $clone->phones = $phones;
        return $clone;
    }

    public function archived(): self
    {
        $clone = clone $this;
        $clone->archived = true;
        return $clone;
    }

    public function build(): Employee
    {
        $employee = new Employee(
            $this->id,
            $this->date,
            $this->name,
            $this->address,
            $this->phones
        );
        if ($this->archived) {
            $employee->archive(new \DateTimeImmutable());
        }
        return $employee;
    }
}
