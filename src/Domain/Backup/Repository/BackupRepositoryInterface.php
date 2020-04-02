<?php

namespace App\Domain\Backup\Repository;

use App\Domain\Backup\Model\Backup;

interface BackupRepositoryInterface
{
    /**
     * @return Backup[]
     */
    public function getBackups(): array;

    /**
     * @param string $name
     * @return Backup
     */
    public function findBackupByName(string $name): Backup;

    /**
     * @param string $name
     * @return array
     */
    public function findBackupsByName(string $name): array;

    /**
     * @param string $name
     * @return mixed
     */
    public function deleteBackup(string $name);
}