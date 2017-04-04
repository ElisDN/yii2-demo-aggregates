<?php

namespace app\entities\Employee;

class EmployeeStatus
{
    private $id;
    private $employee;
    private $status;

    public function __construct(Employee $employee, Status $status)
    {
        $this->employee = $employee;
        $this->status = $status;
    }

    public function getStatus(): Status { return $this->status; }
}
