<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Entity\User;
use App\Domain\User\UserNotFoundException;
use Aura\Sql\ExtendedPdo;

final class UserRepository implements UserRepositoryInterface
{
    /**
     * @var ExtendedPdo
     */
    private $persistence;

    public function __construct(ExtendedPdo $persistence)
    {
        $this->persistence = $persistence;
    }

    public function getUsers(): array
    {
        $rows = $this->persistence->fetchAll('select * from users order by id desc');

        return array_map(function ($row) {
            return User::fromState($row);
        }, $rows);
    }

    public function findUserById(int $id): User
    {
        $row = $this->persistence->fetchOne('select * from users where id = :id', ['id' => $id]);
        if (!$row) {
            throw new UserNotFoundException();
        }

        return User::fromState($row);
    }

    public function findUserByName(string $name): User
    {
        $row = $this->persistence->fetchOne('select * from users where name = :name', ['name' => $name]);
        if (!$row) {
            throw new UserNotFoundException();
        }

        return User::fromState($row);
    }
}