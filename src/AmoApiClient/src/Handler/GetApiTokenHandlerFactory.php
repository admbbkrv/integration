<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoCRM\Client\AmoCRMApiClient;
use Psr\Container\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;

class GetApiTokenHandlerFactory
{
    public function __invoke(ContainerInterface $container) : GetApiTokenHandler
    {
        //Загрузка переменных окружения из .env
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../../../.env');

        $clientId = $_ENV['CLIENT_ID'];
        $clientSecret = $_ENV['CLIENT_SECRET'];
        $redirectUri = $_ENV['CLIENT_REDIRECT_URI'];

        //Запускаем клиент API библиотеки
        $apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);

        return new GetApiTokenHandler($apiClient);
    }
}
