<?php

namespace app\entities\Employee\Events;

use app\entities\Employee\Id;
use app\entities\Employee\Name;

class EmployeeRenamed
{
    public $employee;
    public $name;

    public function __construct(Id $employee, Name $name)
    {
        $this->employee = $employee;
        $this->name = $name;
    }
}
