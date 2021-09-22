<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use http\Exception\InvalidArgumentException;
use Ramsey\Uuid\Nonstandard\Uuid;
use Webmozart\Assert\Assert;

class Id
{
    private $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::uuid($value);

        $this->value = mb_strtolower($value);
    }

    public function getValue()
    {
        return $this->value;
    }

    public static function generate(): self
    {
        return new seld(Uuid::uuid4()->toString());
    }
}