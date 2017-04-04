<?php

namespace app\entities\Employee;

class EmployeePhone
{
    private $id;
    private $employee;
    private $phone;

    public function __construct(Employee $employee, Phone $phone)
    {
        $this->employee = $employee;
        $this->phone = $phone;
    }

    public function getPhone(): Phone { return $this->phone; }
}
