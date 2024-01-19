<?php

declare(strict_types=1);

namespace AmoApiClient\Factory;

use AmoCRM\Client\AmoCRMApiClient;
use Psr\Container\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;

class AmoCRMApiClientFactory
{
    public function __invoke(ContainerInterface $container): AmoCRMApiClient
    {
        //Зграузка переменных окружения
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../../../.env');

        $clientId = $_ENV['CLIENT_ID'];
        $clientSecret = $_ENV['CLIENT_SECRET'];
        $redirectUri = $_ENV['CLIENT_REDIRECT_URI'];

        return new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
    }
}