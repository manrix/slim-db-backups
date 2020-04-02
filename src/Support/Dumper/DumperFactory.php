<?php

declare(strict_types=1);

namespace App\Support\Dumper;

use Spatie\DbDumper\Databases\MySql;
use Spatie\DbDumper\Databases\PostgreSql;
use Spatie\DbDumper\Databases\Sqlite;
use Spatie\DbDumper\DbDumper;

class DumperFactory
{
    /**
     * @var array
     */
    private $settings = [
        'mysql_binary' => '',
        'pgsql_binary' => '',
        'sqlite_binary' => '',
    ];

    /**
     * DumperFactory constructor.
     *
     * @param array $settings
     */
    public function __construct(array $settings = [])
    {
        $this->settings = array_merge($this->settings, $settings);
    }

    /**
     * @param string $driver
     * @return DbDumper
     */
    public function create(string $driver): DbDumper
    {
        switch ($driver) {
            case 'mysql':
                return MySql::create()
                    ->setDumpBinaryPath($this->settings['mysql_binary']);
                break;
            case 'pgsql':
                return PostgreSql::create()
                    ->setDumpBinaryPath($this->settings['pgsql_binary']);
                break;
            case 'sqlite':
                return Sqlite::create()
                    ->setDumpBinaryPath($this->settings['sqlite_binary']);
                break;
            default:
                throw new \InvalidArgumentException("Unknown driver type.");
        }
    }
}