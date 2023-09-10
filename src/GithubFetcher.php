<?php

declare(strict_types=1);

namespace Danilocgsilva\RepositoryHubFetcher;

use GuzzleHttp\Psr7\Request;

class GithubFetcher extends Fetcher
{
    private array $repos = [];

    private string $user;

    private string $password = "";

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;
        return $this;
    }
    
    public function fetches()
    {
        $page = 0;
        $reposArrayRaw = $this->getApiData($page);
        while (count($reposArrayRaw) > 0) {
            foreach ($reposArrayRaw as $rawRepo) {
                $repository = new Repository();
                $repository->setName($rawRepo->name);
                $this->repos[] = $repository;
            }
            $page++;
            $reposArrayRaw = $this->getApiData($page);
        }
    }

    public function getRepos(): array
    {
        if (count($this->repos) === 0) {
           $this->fetches();
        }
        
        return $this->repos;
    }

    private function getApiData(int $page)
    {
        $request = new Request(
            'GET', 
            "https://api.github.com/users/{$this->user}/repos?page={$page}&per_page=20",
            [
                'auth' => $this->getAuthData()
            ]
        );
        $response = $this->httpClient->send($request);
        $stream = $response->getBody();
        $bodyContent = $stream->getContents();
        return json_decode((string) $bodyContent);

    }

    private function getAuthData(): null | array
    {
        if ($this->password === "") {
            return null;
        }
        return [
            $this->user,
            $this->password
        ];
    }
}
