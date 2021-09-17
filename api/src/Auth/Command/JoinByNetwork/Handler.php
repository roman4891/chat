<?php
declare(strict_types=1);

namespace App\Auth\Command\JoinByNetwork;

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
        $email = new Email($command->email);

        if ($this->usersRepository->hasByEmail($email)) {
            throw new \DomainException('User already exists by email!');
        }

        if (!$this->usersRepository->hasByNetwork($identity)) {
            throw new \DomainException('User already exists by network!');
        }

        $user = User::joinByNetwork(
            Id::generate(),
            new \DateTimeImmutable(),
            $email,
            $identity
        );

        $this->usersRepository->add($user);

        $this->flusher->flush();
    }
}