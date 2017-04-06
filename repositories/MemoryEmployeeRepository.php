<?php

namespace app\repositories;

use app\entities\Employee\Employee;
use app\entities\Employee\Id;

class MemoryEmployeeRepository implements EmployeeRepository
{
    private $items = [];

    public function get(Id $id): Employee
    {
        if (!isset($this->items[$id->getId()])) {
            throw new NotFoundException('Employee not found.');
        }
        return clone $this->items[$id->getId()];
    }

    public function add(Employee $employee): void
    {
        $this->items[$employee->getId()->getId()] = $employee;
    }

    public function save(Employee $employee): void
    {
        $this->items[$employee->getId()->getId()] = $employee;
    }

    public function remove(Employee $employee): void
    {
        if ($this->items[$employee->getId()->getId()]) {
            unset($this->items[$employee->getId()->getId()]);
        }
    }
}
