<?php
declare(strict_types=1);

namespace App\Auth\Entity;

interface UsersRepository
{
    public function add(User $user): void;
    public function hasByEmail(Email $email): bool;
}