<?php

declare(strict_types = 1);

namespace Danilocgsilva\RepositoryHubFetcher;

class Repository
{
    private string $name;

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

