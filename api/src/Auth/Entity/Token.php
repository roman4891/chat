<?php

declare(strict_types=1);

namespace App\Auth\Entity;

use DateTimeImmutable;
use DomainException;
use Webmozart\Assert\Assert;

class Token
{
    private string $value;
    private DateTimeImmutable $expires;

    public function __construct(string $value, DateTimeImmutable $expires)
    {
        Assert::uuid($value);
        $this->value = $value;
        $this->expires = $expires;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getExpires(): DateTimeImmutable
    {
        return $this->expires;
    }

    public function validate(string $value, DateTimeImmutable $date): void
    {
        if (!$this->equalTo($value)) {
            throw new DomainException('Token is invalid.');
        }

        if (!$this->isExpiredTo($date)) {
            throw new DomainException('Token is expired.');
        }
    }

    private function equalTo(string $value): bool
    {
        return $this->value === $value;
    }

    private function isExpiredTo(DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }
}