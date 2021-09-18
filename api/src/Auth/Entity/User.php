<?php

declare(strict_types=1);

namespace App\Auth\Entity;

use ArrayObject;
use DateTimeImmutable;
use DomainException;

class User
{
    private Id $id;
    private Email $email;
    private string $passwordHash;
    private DateTimeImmutable $date;
    private ?Token $joinConfirmToken;
    private Status $status;
    private ArrayObject $networks;
    private ?Token $passwordResetToken;

    public function __construct(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        Status $status
    )
    {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->status = $status;
        $this->networks = new ArrayObject();
    }

    public static function requestJoinByEmail(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        string $passwordHash,
        Token $token
    ): self
    {
        $user = new self($id, $date, $email, Status::wait());
        $user->passwordHash = $passwordHash;
        $user->joinConfirmToken = $token;

        return $user;
    }

    public static function joinByNetwork(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        NetworkIdentity $identity
    ): self
    {
        $user = new self($id, $date, $email, Status::active());
        $user->networks->append($identity);

        return $user;
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
            throw new DomainException('Confirmation is not required');
        }

        $this->joinConfirmToken->validate($token, $date);
        $this->status = Status::active();
        $this->joinConfirmToken = null;
    }

    public function attachNetwork(NetworkIdentity $identity): void
    {
        /** @var NetworkIdentity $network */
        foreach ($this->networks as $network) {
            if ($network->isEqualTo($identity)) {
                throw new DomainException('Network is already attached!');
            }
        }

        $this->networks->append($identity);
    }

    public function requestPasswordReset(Token $token, DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active');
        }

        if ($this->passwordResetToken !== null && !$this->passwordResetToken->isExpiredTo($date)) {
            throw new DomainException('Resetting is already requested!');
        }

        $this->passwordResetToken = $token;
    }

    public function resetPassword(string $token, DateTimeImmutable $date, string $hash): void
    {
        if ($this->passwordResetToken === null) {
            throw new DomainException('Resetting is not requested.');
        }
        $this->passwordResetToken->validate($token, $date);
        $this->passwordResetToken = null;
        $this->passwordHash = $hash;
    }
}