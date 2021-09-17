<?php
declare(strict_types=1);

namespace App\Auth\Entity;

use DomainException;

interface UsersRepository
{
    public function hasByEmail(Email $email): bool;
    public function findByConfirmToken($token): ?User;
    public function hasByNetwork($identity): bool;
    public function attachNetwork($identity): void;

    /**
     * @param Id $id
     * @return User
     * @throws DomainException
     */
    public function getId(Id $id): User;
    public function add(User $user): void;
}