<?php

namespace App\Domain\Backup\Model;

class Backup
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $size;

    /**
     * @var integer
     */
    private $timestamp;

    /**
     * @var string
     */
    private $path;

    /**
     * Backup constructor.
     *
     * @param string $name
     * @param string $path
     * @param int $size
     * @param int $timestamp
     */
    public function __construct(string $name, string $path, int $size, int $timestamp)
    {
        $this->name = $name;
        $this->path = $path;
        $this->size = $size;
        $this->timestamp = $timestamp;
    }

    /**
     * Create instance from file.
     *
     * @param string $file
     * @return Backup
     */
    public static function fromFile(string $file)
    {
        $info = pathinfo($file);

        return new Backup(
            $info['basename'],
            realpath($file),
            filesize($file),
            filemtime($file)
        );
    }

    /**
     * @param $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param $size
     */
    public function setSize($size): void
    {
        $this->size = $size;
    }

    /**
     * @param $timestamp
     */
    public function setTimestamp($timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @param $path
     */
    public function setPath($path): void
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}