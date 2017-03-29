<?php

namespace app\repositories;

use app\entities\Employee\Employee;
use app\entities\Employee\Id;
use Ramsey\Uuid\Uuid;

class AREmployeeRepository implements EmployeeRepository
{
    /**
     * @param Id $id
     * @return Employee
     */
    public function get(Id $id): Employee
    {
        if (!$employee = Employee::findOne($id->getId())) {
            throw new NotFoundException('Employee not found.');
        }
        return $employee;
    }

    public function add(Employee $employee): void
    {
        if (!$employee->insert()) {
            throw new \RuntimeException('Adding error.');
        }
    }

    public function save(Employee $employee): void
    {
        if ($employee->update() === false) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Employee $employee): void
    {
        if (!$employee->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}
