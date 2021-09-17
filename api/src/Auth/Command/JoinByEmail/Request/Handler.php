<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Request;

use App\Auth\Entity\Email;
use App\Auth\Entity\Id;
use App\Auth\Entity\Token;
use App\Auth\Entity\User;
use App\Auth\Service\Tokenizer;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use UsersRepository;

class Handler
{
    private UsersRepository $users;
    private PasswordHasher $passwordHasher;
    private Tokenizer $tokenizer;
    private Flusher $flusher;
    private JoinConfirmationSender $sender;

    public function __construct(
        UsersRepository $users,
        PasswordHasher $passwordHasher,
        Tokenizer $tokenizer,
        Flusher $flusher,
        JoinConfirmationSender $sender
    )
    {
        $this->users = $users;
        $this->passwordHasher = $passwordHasher;
        $this->tokenizer = $tokenizer;
        $this->flusher = $flusher;
        $this->sender = $sender;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('User already exist');
        }

        $date = new DateTimeImmutable();

        $token = $this->tokenizer->generate($date);

        $user = User::requestJoinByEmail(
            Id::generate(),
            $date,
            $email,
            $this->passwordHasher->hash($command->password),
            $token
        );

        $this->users->add($user);

        $this->flusher->flush();

        $this->sender->send($email, $token);
    }
}