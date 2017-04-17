<?php

namespace app\bootstrap;

use app\dispatchers\EventDispatcher;
use app\dispatchers\DummyEventDispatcher;
use app\doctrine\EntityManagerBuilder;
use app\doctrine\listeners\TablePrefixSubscriber;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;
use Yii;
use yii\base\BootstrapInterface;

class ContainerBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = Yii::$container;

        $container->setSingleton(EventDispatcher::class, DummyEventDispatcher::class);

        $container->setSingleton(EntityManager::class, function () use ($app) {
            return (new EntityManagerBuilder())
                ->withProxyDir(Yii::getAlias('@runtime/doctrine/proxy'), 'Proxies', !YII_ENV_PROD)
                ->withCache(YII_ENV_PROD ? new FilesystemCache(Yii::getAlias('@runtime/doctrine/cache')) : new ArrayCache())
                ->withMapping(new SimplifiedYamlDriver([]))
                ->withSubscribers([
                    new TablePrefixSubscriber($app->db->tablePrefix),
                ])
                ->build(['pdo' => $app->db->pdo]);
        });
    }
}