<?php

namespace tests\unit\repositories;

use app\entities\Employee\Employee;
use app\repositories\DoctrineEmployeeRepository;
use Doctrine\ORM\EntityManager;
use tests\_fixtures\EmployeeFixture;
use tests\_fixtures\EmployeePhoneFixture;
use ProxyManager\Factory\AccessInterceptorValueHolderFactory;

class DoctrineEmployeeRepositoryTest extends BaseRepositoryTest
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
        ]);

        $em = $this->getEntityManager();

        $repository = new DoctrineEmployeeRepository($em, $em->getRepository(Employee::class));

        $this->repository = (new AccessInterceptorValueHolderFactory())->createProxy($repository, [
            'get' => function () use ($em) { $em->clear(); },
        ]);
    }

    /**
     * @return EntityManager|object
     */
    private function getEntityManager()
    {
        return \Yii::$container->get(EntityManager::class);
    }
}