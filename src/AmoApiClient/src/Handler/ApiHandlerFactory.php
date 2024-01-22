<?php

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoCRM\Client\AmoCRMApiClient;
use Psr\Container\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;

abstract class ApiHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        //Зграузка переменных окружения
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../../../.env');

        $clientId = $_ENV['CLIENT_ID'];
        $clientSecret = $_ENV['CLIENT_SECRET'];
        $redirectUri = $_ENV['CLIENT_REDIRECT_URI'];

        $apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);

        return $this->getApiHandler(
            $apiClient,
            $container->get(GetTokenInterface::class)
        );
    }

    abstract public function getApiHandler(
        AmoCRMApiClient $apiClient,
        GetTokenInterface $getTokenInterface
    ): ApiHandler;
}