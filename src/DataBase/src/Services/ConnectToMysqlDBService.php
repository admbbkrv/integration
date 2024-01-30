<?php

declare(strict_types=1);

namespace DataBase\Services;

use DataBase\Services\Interfaces\ConnectToDBInterface;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Сервис создания подключения к Mysql базе данных
 */
class ConnectToMysqlDBService implements ConnectToDBInterface
{
    /**
     * @inheritDoc
     */
    public function connect(array $config): array
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver' => $config['driver'],
            'host' => $config['host'],
            'database' => $config['database'],
            'username' => $config['username'],
            'password' => $config['password'],
            'charset' => $config['charset'],
            'port' => $config['port'],
            'collation' => $config['collation'],
            'prefix' => $config['prefix'],
        ]);

        $capsule->setAsGlobal();

        $capsule->bootEloquent();

        //Версия базы данных возвращается для проверки подключения
        return Capsule::connection()->select("SELECT VERSION() as version");
    }
}
