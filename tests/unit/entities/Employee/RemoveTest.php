<?php

namespace tests\unit\entities\Employee;

use app\entities\Employee\Events\EmployeeRemoved;
use Codeception\Test\Unit;

class RemoveTest extends Unit
{
    public function testSuccess(): void
    {
        $employee = (new EmployeeBuilder())->archived()->build();

        $employee->remove();

        $this->assertNotEmpty($events = $employee->releaseEvents());
        $this->assertInstanceOf(EmployeeRemoved::class, end($events));
    }

    public function testNotArchived(): void
    {
        $employee = (new EmployeeBuilder())->build();

        $this->expectExceptionMessage('Cannot remove active employee.');

        $employee->remove();
    }
}
