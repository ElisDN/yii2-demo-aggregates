<?php

namespace app\bootstrap;

use app\dispatchers\EventDispatcher;
use app\dispatchers\DummyEventDispatcher;
use app\doctrine\EntityManagerBuilder;
use app\doctrine\listeners\TablePrefixSubscriber;
use app\entities\Employee\Employee;
use app\hydrator\Hydrator;
use app\repositories\doctrine\listeners\EmployeeSubscriber;
use app\repositories\doctrine\types\Employee\IdType;
use app\repositories\DoctrineEmployeeRepository;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;
use Yii;
use app\repositories\EmployeeRepository;
use yii\base\BootstrapInterface;
use yii\di\Container;

class ContainerBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = Yii::$container;

        $container->setSingleton(EventDispatcher::class, DummyEventDispatcher::class);

        $container->setSingleton(Hydrator::class);

        $container->setSingleton(EntityManager::class, function (Container $container) use ($app) {
            return (new EntityManagerBuilder())
                ->withProxyDir(Yii::getAlias('@runtime/doctrine/proxy'), 'Proxies', !YII_ENV_PROD)
                ->withCache(YII_ENV_PROD ? new FilesystemCache(Yii::getAlias('@runtime/doctrine/cache')) : new ArrayCache())
                ->withMapping(new SimplifiedYamlDriver([
                    Yii::getAlias('@app/repositories/doctrine/mapping/Employee') => 'app\entities\Employee',
                ]))
                ->withSubscribers([
                    new TablePrefixSubscriber($app->db->tablePrefix),
                    $container->get(EmployeeSubscriber::class),
                ])
                ->withTypes([
                    IdType::NAME => IdType::class,
                ])
                ->build(['pdo' => $app->db->pdo]);
        });

        $container->setSingleton(EmployeeRepository::class, function (Container $container) {
            /** @var EntityManager $em */
            $em = $container->get(EntityManager::class);
            return new DoctrineEmployeeRepository($em, $em->getRepository(Employee::class));
        });
    }
}
