<?php

declare(strict_types = 1);

namespace Danilocgsilva\RepositoryHubFetcher;

class Commit
{
    private string $sha;
    private string $authorName;
    private string $authorEmail;
    private string $date;
    
    public function setDataFromRaw($rawData): self
    {
        $this->setSha($rawData->sha);
        $this->setAuthorName($rawData->commit->author->name);
        $this->setAuthorEmail($rawData->commit->author->email);
        $this->setDate($rawData->commit->author->date);

        return $this;
    }

    public function setSha(string $sha): self
    {
        $this->sha = $sha;
        return $this;
    }

    public function setAuthorName(string $authorName): self
    {
        $this->authorName = $authorName;
        return $this;
    }

    public function setAuthorEmail(string $authorEmail): self
    {
        $this->authorEmail = $authorEmail;
        return $this;
    }

    public function setDate(string $date)
    {
        $this->date = $date;
        return $this;
    }

    public function getSha(): string
    {
        return $this->sha;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function getAuthorEmail(): string
    {
        return $this->authorEmail;
    }

    public function getDate(): string
    {
        return $this->date;
    }
}
