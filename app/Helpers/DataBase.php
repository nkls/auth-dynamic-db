<?php

namespace App\Helpers;

use App\Resources\Route\ShardCoordinatorResource;
use Exception;
use Illuminate\Support\Facades\DB;

class DataBase
{
    protected const CONNECTION_BLANK = 'blank';
    protected static string $uuid;

    public static function getDefault(): ?string
    {
        return config('database.connections.mysql.database');
    }

    public static function setDefault(string $dbname): void
    {
        config(['database.connections.mysql.database' => $dbname]);
    }

    public static function setDefaultBySubdomain(?string $subdomain): bool
    {
        if (empty($subdomain)) {
            return false;
        }

        if (!$entity = app(ShardCoordinatorResource::class)->getBySubdomain($subdomain)) {
            return false;
        }

        static::setUUID($entity->uuid);
        static::setDefault($entity->dbname);

        return true;
    }

    /**
     * @return string
     * @throws Exception
     */
    public static function getUUID(): string
    {
        return !isset(static::$uuid)
            ? throw new Exception('A database uuid is not set')
            : static::$uuid;
    }

    protected static function setUUID(string $uuid): void
    {
        static::$uuid = $uuid;
    }

    public static function setDefaultByUUID(?string $uuid): bool
    {
        if (empty($uuid)) {
            return false;
        }

        if (!$entity = app(ShardCoordinatorResource::class)->getByUUID($uuid)) {
            return false;
        }

        static::setUUID($uuid);
        static::setDefault($entity->dbname);

        return true;
    }

    public static function create(string $name): bool
    {
        return DB::connection(
            static::addConnection(static::CONNECTION_BLANK)
        )
            ->statement(sprintf(
                'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET %s COLLATE %s;',
                $name,
                config('database.connections.mysql.charset', 'utf8mb4'),
                config('database.connections.mysql.collation', 'utf8mb4_unicode_ci')
            ));
    }

    public static function addConnection(string $connection, string $dbname = null): string
    {
        if (config('database.default') !== 'mysql') {
            return config('database.default');
        }

        if (!config("database.connections.{$connection}")) {
            config(["database.connections.{$connection}" => array_merge(
                config(
                    sprintf('database.connections.%s', config('database.default'))
                ),
                ['database' => $dbname]
            )]);
        }

        return $connection;
    }
}
