<?php

namespace tests\unit\repositories;

use app\repositories\SqlEmployeeRepository;
use app\repositories\Hydrator;
use tests\_fixtures\EmployeeFixture;
use tests\_fixtures\EmployeePhoneFixture;
use tests\_fixtures\EmployeeStatusFixture;
use ProxyManager\Factory\LazyLoadingValueHolderFactory;

class SqlEmployeeRepositoryTest extends BaseRepositoryTest
{
    /**
     * @var \UnitTester
     */
    public $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'employee' => EmployeeFixture::className(),
            'phone' => EmployeePhoneFixture::className(),
            'status' => EmployeeStatusFixture::className(),
        ]);

        $this->repository = new SqlEmployeeRepository(
            \Yii::$app->db,
            new Hydrator(),
            new LazyLoadingValueHolderFactory()
        );
    }
}