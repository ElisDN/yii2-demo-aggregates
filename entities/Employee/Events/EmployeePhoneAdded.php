<?php

namespace app\entities\Employee\Events;

use app\entities\Employee\Id;
use app\entities\Employee\Phone;

class EmployeePhoneAdded
{
    public $employee;
    public $phone;

    public function __construct(Id $employee, Phone $phone)
    {
        $this->employee = $employee;
        $this->phone = $phone;
    }
}
