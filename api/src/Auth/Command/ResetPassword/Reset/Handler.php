<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Reset;

use App\Auth\Entity\Email;
use App\Auth\Entity\UsersRepository;
use App\Aut\Service\Tokenizer;
use App\Auth\Service\PasswordHasher;
use App\Auth\Service\PasswordResetTokenSender;
use App\Flusher;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private UsersRepository $users;
    private PasswordHasher $hasher;
    private Flusher $flusher;

    public function __construct(
        UsersRepository $users,
        PasswordHasher $hasher,
        Flusher $flusher
    )
    {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        if (!$user = $this->users->findByPasswordResetToken($command->token)) {
            throw new DomainException('Token is not found!');
        }

        $user->resetPassword(
            $command->token,
            new DateTimeImmutable(),
            $this->hasher->hash($command->password)
        );

        $this->flusher->flush();
    }
}