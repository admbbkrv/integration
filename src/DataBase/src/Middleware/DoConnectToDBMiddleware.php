<?php

declare(strict_types=1);

namespace DataBase\Middleware;

use DataBase\Services\Interfaces\ConnectToDBInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware для создания подключения к БД
 */
class DoConnectToDBMiddleware implements MiddlewareInterface
{
    /**
     * @var ConnectToDBInterface
     */
    private ConnectToDBInterface $connectToDB;
    /**
     * @var array
     */
    private array $dataBaseConfig;

    public function __construct(
        ConnectToDBInterface $connectToDB,
        array $dataBaseConfig
    ) {
        $this->connectToDB = $connectToDB;
        $this->dataBaseConfig = $dataBaseConfig;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        $this->connectToDB->connect($this->dataBaseConfig);

        return $handler->handle($request);
    }
}
