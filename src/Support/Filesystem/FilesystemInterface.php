<?php

namespace App\Support\Filesystem;

interface FilesystemInterface
{
    public function list(string $pattern): array;

    public function delete(string $file): bool;

    public function getFilePath(string $file): string;
}