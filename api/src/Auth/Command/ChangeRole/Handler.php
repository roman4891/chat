<?php
declare(strict_types=1);

namespace App\Auth\Command\ChangeRole;


use App\Auth\Entity\Id;
use App\Auth\Entity\UsersRepository;
use App\Flusher;

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

        $user->changeRole(new Role($command->role));

        $this->flusher->flush();
    }
}