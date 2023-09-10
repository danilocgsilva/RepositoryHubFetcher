<?php

namespace Danilocgsilva\RepositoryHubFetcher;

use GuzzleHttp\Client as HttpClient;

class Fetcher
{
    public function fetches()
    {
        $httpClient = new HttpClient();
        return "hello world!";
    }
}
