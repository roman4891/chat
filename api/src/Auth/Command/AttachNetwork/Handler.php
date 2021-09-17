<?php
declare(strict_types=1);

namespace App\Auth\Command\AttachNetwork;

use App\Auth\Entity\Email;
use App\Auth\Entity\Id;
use App\Auth\Entity\NetworkIdentity;
use App\Auth\Entity\User;
use App\Auth\Entity\UsersRepository;

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
        $identity = new NetworkIdentity($command->network, $command->identity);

        if ($this->usersRepository->hasByNetwork($identity)) {
            throw new \DomainException('User with this network already exists!');
        }

        $user = $this->usersRepository->get(new Id($command->id));

        $user->attachNetwork($identity);

        $this->flusher->flush();
    }
}