<?php

declare(strict_types=1);

namespace Danilocgsilva\RepositoryHubFetcher;

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
        $page = 1;

        $url = "https://api.github.com/users/{$this->githubUserDirectory}/repos?page={$page}&per_page={$this->pageCount}";
        $reposArrayRaw = $this->getApiData($url);
        while (count($reposArrayRaw) > 0) {
            foreach ($reposArrayRaw as $rawRepo) {
                $repository = new Repository();
                $repository->setDataFromRaw($rawRepo);
                $this->repos[] = $repository;
            }
            $page++;
            $url = "https://api.github.com/users/{$this->githubUserDirectory}/repos?page={$page}&per_page={$this->pageCount}";
            $reposArrayRaw = $this->getApiData($url);
        }
    }

    public function getRepos(): array
    {
        if (count($this->repos) === 0) {
           $this->fetches();
        }
        
        return $this->repos;
    }

    public function getCommits(Repository $repository): array
    {
        $url = "https://api.github.com/repos/{$this->githubUserDirectory}/{$repository->getName()}/commits";
        $commitsRawData = $this->getApiData($url);

        $commits = [];
        foreach ($commitsRawData as $commitData) {
            $commits[] = (new Commit())->setDataFromRaw($commitData);
        }

        return $commits;
    }

    /**
     * Burst data from api.
     *
     * @param integer $page
     * @return array
     */
    private function getApiData(string $url): array
    {
        $invalidUrlChars = ["/\//", "/:/", "/\?/", "/=/", "/&/", "/-/"];
        $urlAsCacheKey = preg_replace($invalidUrlChars, "_", $url);
        $cachedData = $this->storage->getItem($urlAsCacheKey);
        if (!$cachedData->isHit()) {
            $response = $this->httpClient->get(
                $url,
                [
                    'auth' => $this->getAuthData()
                ]
            );

            $stream = $response->getBody();
            $bodyContent = $stream->getContents();

            $bodyContentString = (string) $bodyContent;

            $cachedData->set($bodyContentString);
            $cachedData->expiresAfter($this->cacheSecondsTime);
            $this->storage->save($cachedData);
        }

        if ($this->storage->hasItem($urlAsCacheKey)) {
            $retrievedData = $this->storage->getItem($urlAsCacheKey);
            $bodyContentString = $retrievedData->get();
        }

        return json_decode($bodyContentString);
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
