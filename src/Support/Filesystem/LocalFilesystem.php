<?php

namespace App\Support\Filesystem;

class LocalFilesystem implements FilesystemInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * LocalFilesystem constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = trim(realpath($path), '/');
    }

    /**
     * @param string $pattern
     * @return array
     */
    public function list(string $pattern): array
    {
        return glob(sprintf('%s/%s', $this->path, $pattern));
    }

    /**
     * @param string $file
     * @return bool
     * @throws FileNotFoundException
     */
    public function delete(string $file): bool
    {
        return unlink($this->getFilePath($file));
    }

    /**
     * @param string $file
     * @return string
     * @throws FileNotFoundException
     */
    public function getFilePath(string $file): string
    {
        $path = realpath(sprintf('%s/%s', $this->path, $file));
        if (!$path) {
            throw new FileNotFoundException(sprintf("File %s not found in %s", $file, $this->path));
        }

        return $path;
    }
}