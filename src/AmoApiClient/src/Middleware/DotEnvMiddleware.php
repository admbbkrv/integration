<?php

declare(strict_types=1);

namespace AmoApiClient\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Dotenv\Dotenv;

class DotEnvMiddleware implements MiddlewareInterface
{
    private Dotenv $dotenv;

    public function __construct(Dotenv $dotenv)
    {
        $this->dotenv = $dotenv;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $this->dotenv->load(__DIR__ . '/../../../../.env');

        return $handler->handle($request);
    }
}
