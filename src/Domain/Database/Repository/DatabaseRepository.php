<?php

namespace App\Domain\Database\Repository;

use App\Domain\Database\DatabaseNotFoundException;
use App\Domain\Database\Entity\Database;
use Aura\Sql\ExtendedPdo;

final class DatabaseRepository implements DatabaseRepositoryInterface
{
    /**
     * @var array
     */
    private $persistence;

    public function __construct(ExtendedPdo $persistence)
    {
        $this->persistence = $persistence;
    }

    /**
     * @inheritDoc
     */
    public function getDatabases(): array
    {
        $rows = $this->persistence->fetchAll('select * from databases');

        return array_map(function ($row) {
            return Database::fromState($row);
        }, $rows);
    }

    /**
     * @inheritDoc
     */
    public function findDatabaseById(int $id): Database
    {
        $row = $this->persistence->fetchOne('select * from databases where id = :id', ['id' => $id]);
        if (!$row) {
            throw new DatabaseNotFoundException();
        }

        return Database::fromState($row);
    }

    /**
     * @inheritDoc
     */
    public function findDatabaseByName(string $name): Database
    {
        $row = $this->persistence->fetchOne('select * from databases where name = :name', ['name' => $name]);
        if (!$row) {
            throw new DatabaseNotFoundException();
        }

        return Database::fromState($row);
    }

    /**
     * @inheritDoc
     */
    public function findDatabasesByName(string $name): array
    {
        $rows = $this->persistence->fetchAll('select * from databases where name = :name', ['name' => $name]);

        return array_map(function ($row) {
            return Database::fromState($row);
        }, $rows);
    }
}