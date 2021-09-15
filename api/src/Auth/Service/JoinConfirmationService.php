<?php

declare(strict_types=1);

namespace App\Auth\Service;

use App\Auth\Entity\Email;
use App\Auth\Entity\Token;

interface JoinConfirmationService
{
    public function send(Email $email, Token $token): void;
}