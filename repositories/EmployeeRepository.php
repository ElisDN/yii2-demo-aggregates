<?php

namespace app\repositories;

use app\entities\Employee\Employee;
use app\entities\Employee\Id;

interface EmployeeRepository
{
    /**
     * @param Id $id
     * @return Employee
     * @throws NotFoundException
     */
    public function get(Id $id): Employee;

    public function add(Employee $employee): void;

    public function save(Employee $employee): void;

    public function remove(Employee $employee): void;
}
