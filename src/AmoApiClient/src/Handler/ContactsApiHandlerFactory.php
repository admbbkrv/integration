<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoCRM\Client\AmoCRMApiClient;
use Mezzio\Router\RouterInterface;

class ContactsApiHandlerFactory extends ApiHandlerFactory
{
    public function getApiHandler(AmoCRMApiClient $apiClient, GetTokenInterface $getTokenInterface, RouterInterface $router): ApiHandler
    {
        return new ContactsApiHandler($apiClient, $getTokenInterface, $router);
    }
}
