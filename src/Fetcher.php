<?php

declare(strict_types=1);

namespace Danilocgsilva\RepositoryHubFetcher;

use GuzzleHttp\Client as HttpClient;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Cache\Adapter\Filesystem\FilesystemCachePool;

abstract class Fetcher
{
    protected HttpClient $httpClient;
    protected $storage;

    /**
     * Interval for cache expiration
     *
     * @var integer
     */
    protected int $cacheSecondsTime = 86400;

    public function __construct()
    {
        $this->httpClient = new HttpClient();
        $this->buildStorage();
    }

    /**
     * Changes the default cache expiration time in seconds
     *
     * @param integer $cacheSecondsTime
     * @return self
     */
    public function setCacheSecondsTime(int $cacheSecondsTime): self
    {
        $this->cacheSecondsTime = $cacheSecondsTime;
        return $this;
    }

    private function buildStorage()
    {
        $filesystemAdapter = new Local('/tmp/repository-hub-fetcher/cache');
        $filesystem = new Filesystem($filesystemAdapter);
        
        $this->storage = new FilesystemCachePool($filesystem);
    }

}
