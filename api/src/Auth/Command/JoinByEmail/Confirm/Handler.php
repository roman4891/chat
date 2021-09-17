<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Confirm;


use App\Auth\Entity\Token;
use UsersRepository;

class Handler
{
    private UsersRepository $users;
    private Flusher $flusher;

    public function __construct(UsersRepository $users, Flusher $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        if (!$user = $this->users->findByConfirmToken($command->token)) {
            throw new DomainException('Incorrect Token.');
        }

        $user->confirmJoin($command->token, new \DateTimeImmutable());

        $this->flusher->flush();
    }
}