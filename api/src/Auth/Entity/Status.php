<?php
declare(strict_types=1);

namespace App\Auth\Entity;

class Status
{
    public const WAIT = 'wait';
    public const ACTIVE = 'active';

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function wait(): self
    {
        return new self(self::WAIT);
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public function isActive(): bool
    {
        return $this->name === self::ACTIVE;
    }

    public function isWait(): bool
    {
        return $this->name === self::WAIT;
    }
}