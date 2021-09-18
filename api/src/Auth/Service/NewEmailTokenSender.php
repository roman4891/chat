<?php

declare(strict_types=1);

namespace App\Auth\Service;

interface NewEmailTokenSender
{
    public function send($email, $token): void;
}