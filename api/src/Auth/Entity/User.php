<?php

declare(strict_types=1);

namespace App\Auth\Entity;

use DateTimeImmutable;

class User
{
    private string $id;
    private string $email;
    private string $hash;
    private DateTimeImmutable $date;
    private string $token;

    public function __construct(
        string $id,
        DateTimeImmutable $date,
        string $email,
        string $hash,
        string $token
    )
    {
        $this->id = $id;
        $this->date = $date;
        $this->email = strtolower($email);
        $this->hash = $hash;
        $this->token = $token;
    }

    public function getId(): string
    {
        return $this->id;
    }
}