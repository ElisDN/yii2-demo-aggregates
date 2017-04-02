<?php

namespace app\entities\Employee\Events;

use app\entities\Employee\Id;
use app\entities\Employee\Address;

class EmployeeAddressChanged
{
    public $employee;
    public $address;

    public function __construct(Id $employee, Address $address)
    {
        $this->employee = $employee;
        $this->address = $address;
    }
}
