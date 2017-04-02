<?php

namespace app\entities\Employee\Events;

use app\entities\Employee\Id;

class EmployeeCreated
{
    public $employee;

    public function __construct(Id $employee)
    {
        $this->employee = $employee;
    }
}
