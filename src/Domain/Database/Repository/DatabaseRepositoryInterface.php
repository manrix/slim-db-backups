<?php

namespace App\Domain\Database\Repository;

use App\Domain\Database\Entity\Database;

interface DatabaseRepositoryInterface
{
    /**
     * @return array
     */
    public function getDatabases(): array;

    /**
     * @param int $id
     * @return Database
     */
    public function findDatabaseById(int $id): Database;

    /**
     * @param string $name
     * @return Database
     */
    public function findDatabaseByName(string $name): Database;

    /**
     * @param string $name
     * @return array
     */
    public function findDatabasesByName(string $name): array;
}