<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Request;

use App\Auth\Entity\User;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class Handler
{
    private UsersRepository $users;

    public function __construct(UsersRepository $users)
    {
        $this->users = $users;
    }

    public function handle(Command $command): void
    {
        $email = strtolower($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('User already exist');
        }

        $user = new User(
            Uuid::uuid4()->toString(),
            new DateTimeImmutable(),
            $email,
            password_hash($command->password, PASSWORD_ARGON2I),
            Uuid::uuid4()->toString(),
        );

        $this->users->add($user);
    }
}