<?php

namespace tests\unit\repositories;

use app\repositories\AREmployeeRepository;
use app\tests\_fixtures\EmployeeFixture;
use app\tests\_fixtures\EmployeePhoneFixture;
use app\tests\_fixtures\EmployeeStatusFixture;

class AREmployeeRepositoryTest extends BaseRepositoryTest
{
    /**
     * @var \UnitTester
     */
    public $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'employee' => EmployeeFixture::className(),
            'employee_phone' => EmployeePhoneFixture::className(),
            'employee_status' => EmployeeStatusFixture::className(),
        ]);

        $this->repository = new AREmployeeRepository();
    }
}