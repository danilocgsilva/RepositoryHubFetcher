<?php

declare(strict_types=1);

namespace Danilocgsilva\RepositoryHubFetcher;

use GuzzleHttp\Client as HttpClient;
use Laminas\Cache\ConfigProvider;
use Laminas\Cache\Service\StorageAdapterFactoryInterface;
use Laminas\Cache\Storage\Adapter\Memory;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ServiceManager\ServiceManager;

abstract class Fetcher
{
    protected HttpClient $httpClient;
    protected $storage;

    public function __construct()
    {
        $this->httpClient = new HttpClient();
        $this->buildStorage();
    }

    private function buildStorage()
    {
        $config = (new ConfigAggregator([
            ConfigProvider::class,
        ]))->getMergedConfig();

        $dependencies = $config['dependencies'];

        $container = new ServiceManager($dependencies);

        /** @var StorageAdapterFactoryInterface $storageFactory */
        $storageFactory = $container->get(StorageAdapterFactoryInterface::class);

        $this->storage = $storageFactory->create(Memory::class);

        // $storage->setItem('foo', 'bar');
    }
}
