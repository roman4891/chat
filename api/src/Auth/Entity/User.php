<?php

declare(strict_types=1);

namespace App\Auth\Entity;

use DateTimeImmutable;

class User
{
    private Id $id;
    private Email $email;
    private string $passwordHash;
    private DateTimeImmutable $date;
    private ?Token $joinConfirmToken;
    private Status $status;

    public function __construct(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        string $passwordHash,
        Token $token
    )
    {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->joinConfirmToken = $token;
        $this->status = Status::wait();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getJoinConfirmToken(): Token
    {
        return $this->joinConfirmToken;
    }

    public function isWait(): bool
    {
        return $this->status === $this->status->isWait();
    }

    public function isActive(): bool
    {
        return $this->status === $this->status->isActive();
    }

    public function confirmJoin(string $token, DateTimeImmutable $date): void
    {
        if ($this->joinConfirmToken === null) {
            throw new \DomainException('Confirmation is not required');
        }

        $this->joinConfirmToken->validate($token, $date);
        $this->status = Status::active();
        $this->joinConfirmToken = null;
    }
}