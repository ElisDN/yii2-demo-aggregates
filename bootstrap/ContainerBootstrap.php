<?php

namespace app\bootstrap;

use app\dispatchers\EventDispatcher;
use app\dispatchers\DummyEventDispatcher;
use app\repositories\AREmployeeRepository;
use app\repositories\EmployeeRepository;
use ProxyManager\Factory\LazyLoadingValueHolderFactory;
use yii\base\BootstrapInterface;

class ContainerBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(EventDispatcher::class, DummyEventDispatcher::class);

        $container->setSingleton(LazyLoadingValueHolderFactory::class);

        $container->setSingleton(EmployeeRepository::class, AREmployeeRepository::class);
    }
}