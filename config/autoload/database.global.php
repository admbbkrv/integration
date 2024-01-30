<?php

declare(strict_types=1);

return [
    'database' => [
        'driver' => 'mysql',
        'host' => getenv('MYSQL_HOST') ?: '',
        'database' => getenv('MYSQL_DATABASE') ?: '',
        'username' => getenv('MYSQL_USER') ?: '',
        'password' => getenv('MYSQL_PASSWORD') ?: '',
        'charset' => getenv('MYSQL_CHARSET') ?: 'utf8',
        'port' => getenv('MYSQL_PORT') ?: 3306,
        'collation' => getenv('MYSQL_COLLATION') ?: 'utf8_unicode_ci',
        'prefix' => '',
    ],
];
