<?php

namespace app\bootstrap;

use app\dispatchers\EventDispatcher;
use app\dispatchers\DummyEventDispatcher;
use app\repositories\Hydrator;
use app\repositories\SqlEmployeeRepository;
use app\repositories\EmployeeRepository;
use yii\base\BootstrapInterface;
use yii\di\Instance;

class ContainerBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(EventDispatcher::class, DummyEventDispatcher::class);

        $container->setSingleton(Hydrator::class);

        $container->setSingleton('db', function () use ($app) {
            return $app->db;
        });

        $container->setSingleton(EmployeeRepository::class, SqlEmployeeRepository::class, [
            Instance::of('db'),
        ]);
    }
}