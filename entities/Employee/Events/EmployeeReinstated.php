<?php

namespace app\entities\Employee\Events;

use app\entities\Employee\Id;

class EmployeeReinstated
{
    public $employee;
    public $date;

    public function __construct(Id $employee, \DateTimeImmutable $date)
    {
        $this->employee = $employee;
        $this->date = $date;
    }
}
