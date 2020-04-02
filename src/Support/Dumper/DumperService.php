<?php

declare(strict_types=1);

namespace App\Support\Dumper;

use Spatie\DbDumper\DbDumper;

class DumperService
{
    /**
     * @var string
     */
    private $path;

    /**
     * DumperService constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->setPath($path);
    }

    /**
     * @param DbDumper $dumper
     * @param string $file
     * @return mixed
     */
    public function dump(DbDumper $dumper, string $file)
    {
        return $dumper->dumpToFile(sprintf('%s/%s', realpath($this->path), $file));
    }

    /**
     * @param string $path
     * @return DumperService
     */
    public function setPath(string $path): DumperService
    {
        if (!is_dir($path)) {
            throw new \InvalidArgumentException('The provided path is not a directory.');
        }

        if (!is_writable($path)) {
            throw new \InvalidArgumentException('The provided path is not writable.');
        }

        $this->path = $path;

        return $this;
    }
}