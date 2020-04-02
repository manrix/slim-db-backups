<?php

namespace App\Domain\Database\Entity;

class Database
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $driver;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string|null
     */
    private $password;

    /**
     * @var string|null
     */
    private $updated_at;

    /**
     * @var string|null
     */
    private $created_at;

    /**
     * Database constructor.
     *
     * @param string $name
     * @param string $driver
     * @param string|null $user
     * @param string|null $password
     */
    public function __construct(string $name, string $driver, ?string $user = null, ?string $password = null)
    {
        $this->setName($name);
        $this->setDriver($driver);
        $this->setUser($user);
        $this->setPassword($password);
    }

    /**
     * @param array $state
     * @return Database
     */
    public static function fromState(array $state)
    {
        $database = new Database(
            $state['name'],
            $state['driver'],
            $state['user'],
            $state['password']
        );

        if (isset($state['id'])) {
            $database->setId($state['id']);
        }

        return $database;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * @param string $driver
     */
    public function setDriver(string $driver): void
    {
        $this->driver = $driver;
    }

    /**
     * @return string|null
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    /**
     * @param string|null $updated_at
     */
    public function setUpdatedAt(?string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    /**
     * @param string|null $created_at
     */
    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * Return the file name used for backups.
     *
     * @param string $extension
     * @return string
     */
    public function getBackupFileName(string $extension = 'sql')
    {
        return sprintf('%s_%s.%s', date('YmdHis'), $this->getName(), $extension);
    }
}