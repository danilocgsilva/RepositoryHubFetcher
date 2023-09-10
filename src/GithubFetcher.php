<?php

declare(strict_types=1);

namespace Danilocgsilva\RepositoryHubFetcher;

use GuzzleHttp\Psr7\Request;

class GithubFetcher extends Fetcher
{
    /**
     * Array of Repository lists
     */
    private array $repos = [];

    /**
     * Github user for login, if needed.
     *
     * @var string
     */
    private string $user;

    /**
     * The name of user`s directory in Github
     *
     * @var string
     */
    private string $githubUserDirectory;

    /**
     * Password for Github login, if required
     *
     * @var string
     */
    private string $password = "";

    /**
     * Github pagination for api
     *
     * @var integer
     */
    private int $pageCount = 100;

    /**
     * Github user password (not required).
     * 
     * If you requests so much times responses from
     *   Github api, it halts you. Them, setting a login enlarges the requests
     *   by time allowed.
     * 
     * @param string $password
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Github user directory, where the repositories relies on.
     *
     * @param string $githubUserDirectory
     * @return self
     */
    public function setGithubUserDirectory(string $githubUserDirectory): self
    {
        $this->githubUserDirectory = $githubUserDirectory;
        return $this;
    }

    /**
     * Github user (not required).
     * 
     * You can fetch Github api without user data. But if asks much times
     *  for api data, you is halted. But given a user name (together with)
     *  a user password) the api limit is enlatged.
     * 
     * @param string $user
     * @return self
     */
    public function setUser(string $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Changes pagecount pagination default amount.
     *
     * @param integer $pageCount
     * @return self
     */
    public function setPageCount(int $pageCount): self
    {
        $this->pagetCount = $pageCount;
        return $this;
    }
    
    /**
     * Fills the repository array
     *
     * @return void
     */
    public function fetches(): void
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

    /**
     * Burst data from api.
     *
     * @param integer $page
     * @return array
     */
    private function getApiData(int $page): array
    {
        $request = new Request(
            'GET', 
            "https://api.github.com/users/{$this->githubUserDirectory}/repos?page={$page}&per_page={$this->pageCount}",
            [
                'auth' => $this->getAuthData()
            ]
        );
        $response = $this->httpClient->send($request);
        $stream = $response->getBody();
        $bodyContent = $stream->getContents();
        return json_decode((string) $bodyContent);

    }

    /**
     * Abstracts the auth data in a suitable format for data api burst.
     *
     * @return void|array
     */
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
