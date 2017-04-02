<?php

namespace app\entities\Employee\Events;

use app\entities\Employee\Id;

class EmployeeRemoved
{
    public $employee;

    public function __construct(Id $employee)
    {
        $this->employee = $employee;
    }
}
