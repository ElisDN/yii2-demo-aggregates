<?php

namespace app\doctrine;

use Doctrine\Common\Cache\CacheProvider;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;

class EntityManagerBuilder
{
    private $proxyNamespace;
    private $proxyDir;
    private $proxyAutoGenerate;
    private $cacheProvider;
    private $mappingDriver;
    private $subscribers = [];
    private $listeners = [];
    private $types = [];

    public function withProxyDir($dir, $namespace, $autoGenerate): self
    {
        $this->proxyDir = $dir;
        $this->proxyNamespace = $namespace;
        $this->proxyAutoGenerate = $autoGenerate;
        return $this;
    }

    public function withCache(CacheProvider $cache): self
    {
        $this->cacheProvider = $cache;
        return $this;
    }

    public function withMapping(MappingDriver $driver): self
    {
        $this->mappingDriver = $driver;
        return $this;
    }

    public function withSubscribers(array $subscribers): self
    {
        $this->subscribers = $subscribers;
        return $this;
    }

    public function withListeners(array $listeners): self
    {
        $this->listeners = $listeners;
        return $this;
    }

    public function withTypes(array $types): self
    {
        $this->types = $types;
        return $this;
    }

    public function build($params): EntityManager
    {
        $this->checkParameters();

        $config = new Configuration();

        $config->setProxyDir($this->proxyDir);
        $config->setProxyNamespace($this->proxyNamespace);
        $config->setAutoGenerateProxyClasses($this->proxyAutoGenerate);

        $config->setMetadataDriverImpl($this->mappingDriver);

        if (!$this->cacheProvider) {
            $config->setMetadataCacheImpl($this->cacheProvider);
            $config->setQueryCacheImpl($this->cacheProvider);
        }

        $evm = new EventManager();

        foreach ($this->subscribers as $subscriber) {
            $evm->addEventSubscriber($subscriber);
        }

        foreach ($this->listeners as $name => $listener) {
            $evm->addEventListener($name, $listener);
        }

        foreach ($this->types as $name => $type) {
            if (Type::hasType($name)) {
                Type::overrideType($name, $type);
            } else {
                Type::addType($name, $type);
            }
        }

        return EntityManager::create($params, $config, $evm);
    }

    private function checkParameters(): void
    {
        if (empty($this->proxyDir) || empty($this->proxyNamespace)) {
            throw new \InvalidArgumentException('Specify proxy settings.');
        }

        if (!$this->mappingDriver) {
            throw new \InvalidArgumentException('Specify mapping driver.');
        }
    }
}