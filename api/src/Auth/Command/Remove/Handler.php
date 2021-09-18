<?php

declare(strict_types=1);

namespace App\Auth\Command\Remove;

use App\Auth\Entity\UsersRepository;
use App\Flusher;
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
        $user = $this->usersRepository->get(new Id($command->id));

        $user->remove();

        $this->usersRepository->remove($user);

        $this->flusher->flush();
    }
}