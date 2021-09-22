<?php

declare(strict_types=1);

namespace App\Auth\Entity;


use Webmozart\Assert\Assert;

class Network
{
    private string $name;
    private string $identity;

    public function __construct(string $name, string $identity)
    {
        Assert::notEmpty($name);
        Assert::notEmpty($identity);
        $this->name = mb_strtolower($name);
        $this->identity = mb_strtolower($identity);
    }

    public function isEqualTo(self $network): bool
    {
        return
            $this->getIdentity() === $network->getNetwork() &&
            $this->getName() === $network->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }
}