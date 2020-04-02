<?php

namespace App\Domain\Backup\Repository;

use App\Domain\Backup\Model\Backup;
use App\Domain\Backup\BackupNotFoundException;
use App\Support\Filesystem\FileNotFoundException;
use App\Support\Filesystem\FilesystemInterface;

final class FileBackupRepository implements BackupRepositoryInterface
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * FileBackupRepository constructor.
     *
     * @param FilesystemInterface $filesystem
     */
    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @return Backup[]
     */
    public function getBackups(): array
    {
        return array_map(function ($backup) {
            return Backup::fromFile($backup);
        }, $this->filesystem->list('*'));
    }

    /**
     * @param string $name
     * @return Backup
     */
    public function findBackupByName(string $name): Backup
    {
        try {
            return Backup::fromFile($this->filesystem->getFilePath($name));
        } catch (FileNotFoundException $exception) {
            throw new BackupNotFoundException();
        }
    }

    /**
     * @param string $name
     * @return Backup[]
     */
    public function findBackupsByName(string $name): array
    {
        $backups = array_filter($this->filesystem->list('*'), function ($file) use ($name) {
            return strpos($name, basename($file)) !== false;
        });

        return array_map(function ($backup) {
            return Backup::fromFile($backup);
        }, $backups);
    }

    /**
     * @param string $name
     * @return bool|mixed
     */
    public function deleteBackup(string $name)
    {
        try {
            return $this->filesystem->delete($name);
        } catch (FileNotFoundException $exception) {
            throw new BackupNotFoundException();
        }
    }
}