<?php

declare(strict_types=1);

namespace Danilocgsilva\RepositoryHubFetcher;

use GuzzleHttp\Client as HttpClient;

abstract class Fetcher
{
    protected HttpClient $httpClient;

    public function __construct()
    {
        $this->httpClient = new HttpClient();
    }
}
