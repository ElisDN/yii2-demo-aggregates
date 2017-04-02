<?php

namespace tests\unit\entities\Employee;

use app\entities\Employee\Address;
use app\entities\Employee\Events\EmployeeAddressChanged;
use Codeception\Test\Unit;

class ChangeAddressTest extends Unit
{
    public function testSuccess(): void
    {
        $employee = (new EmployeeBuilder())->build();

        $employee->changeAddress($address = new Address('New', 'Test', 'Address', 'Street', '25a'));
        $this->assertEquals($address, $employee->getAddress());

        $this->assertNotEmpty($events = $employee->releaseEvents());
        $this->assertInstanceOf(EmployeeAddressChanged::class, end($events));
    }
}
