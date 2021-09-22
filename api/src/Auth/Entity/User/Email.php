<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use http\Exception\InvalidArgumentException;
use Webmozart\Assert\Assert;

class Email
{
    private $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::email($value);

        $this->value = mb_strtolower($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqualTo(self $other): bool
    {
        return $this->value === $other->value;
    }
}