<?php

declare(strict_types = 1);

namespace Danilocgsilva\RepositoryHubFetcher;

class Repository
{
    private string $name;
    private string $htmlUrl;
    private string $gitUrl;
    private string $cloneUrl;

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setDataFromRaw($rawData)
    {
        $this->setName($rawData->name);
        $this->setHtmlUrl($rawData->html_url);
        $this->setGitUrl($rawData->git_url);
        $this->setCloneUrl($rawData->clone_url);
    }

    public function setHtmlUrl(string $htmlUrl): self
    {
        $this->htmlUrl = $htmlUrl;
        return $this;
    }

    public function getHtmlUrl(): string
    {
        return $this->htmlUrl;
    }

    public function setGitUrl(string $gitUrl): self
    {
        $this->gitUrl = $gitUrl;
        return $this;
    }

    public function getGitUrl(): string
    {
        return $this->gitUrl;
    }

    public function setCloneUrl(string $cloneUrl): self
    {
        $this->cloneUrl = $cloneUrl;
        return $this;
    }

    public function getCloneUrl(): string
    {
        return $this->cloneUrl;
    }
}

