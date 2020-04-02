<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;

interface UserRepositoryInterface
{
    public function getUsers(): array;

    public function findUserById(int $id): User;

    public function findUserByName(string $name): User;
}