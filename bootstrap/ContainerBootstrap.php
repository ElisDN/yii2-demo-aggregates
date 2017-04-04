<?php

namespace app\bootstrap;

use app\dispatchers\EventDispatcher;
use app\dispatchers\DummyEventDispatcher;
use app\doctrine\listeners\TablePrefixSubscriber;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
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
            $config = new Configuration();

            $config->setProxyDir(Yii::getAlias('@runtime/doctrine/proxy'));
            $config->setProxyNamespace('Proxies');
            $config->setAutoGenerateProxyClasses(!YII_ENV_PROD);

            $cache = YII_ENV_PROD ? new FilesystemCache(Yii::getAlias('@runtime/doctrine/cache')) : new ArrayCache();

            $config->setMetadataCacheImpl($cache);
            $config->setQueryCacheImpl($cache);

            $config->setMetadataDriverImpl(new SimplifiedYamlDriver([]));

            $evm = new EventManager();

            $evm->addEventSubscriber(new TablePrefixSubscriber($app->db->tablePrefix));

            return EntityManager::create(['pdo' => $app->db->pdo], $config, $evm);
        });
    }
}