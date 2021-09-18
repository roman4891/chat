<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Confirm;

use App\Auth\Entity\UsersRepository;
use App\Flusher;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private UsersRepository $usersRepository;
    private Flusher $flusher;

    public function __construct(UsersRepository $usersRepository, Flusher $flusher)
    {
        $this->usersRepository = $usersRepository;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        if (!$user = $this->usersRepository->findByNewEmailToken($command->token)) {
            throw new DomainException('Incorrect email.');
        }

        $user->confirmEmailChanging(
            $command->token,
            new DateTimeImmutable()
        );

        $this->flusher->flush();
    }
}