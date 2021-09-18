<?php
declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Request;

use App\Auth\Entity\Email;
use App\Auth\Entity\UsersRepository;
use App\Auth\Service\NewEmailTokenSender;
use App\Auth\Service\Tokenizer;
use App\Flusher;
use DateTimeImmutable;
use DomainException;

class Handler
{
    private UsersRepository $usersRepository;
    private Tokenizer $tokenizer;
    private NewEmailTokenSender $sender;
    private Flusher $flusher;

    public function __construct(
        UsersRepository $usersRepository,
        Tokenizer $tokenizer,
        NewEmailTokenSender $sender,
        Flusher $flusher
    )
    {
        $this->usersRepository = $usersRepository;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->usersRepository->get(new Id($command->id));

        $email = new Email($command->email);

        if ($this->usersRepository->hasByEmail($email)) {
            throw new DomainException('Email is already in use.');
        }

        $date = new DateTimeImmutable();

        $user->requestEmailChanging(
            $token = $this->tokenizer->generate($date),
            $date,
            $email
        );

        $this->flusher->flush();

        $this->sender->send($email, $token);
    }
}