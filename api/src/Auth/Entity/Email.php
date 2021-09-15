<?php

declare(strict_types=1);

namespace App\Auth\Entity;

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

    public function getValue()
    {
        return $this->value;
    }
}