<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoCRM\Client\AmoCRMApiClient;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GetApiTokenHandler implements RequestHandlerInterface
{
    /**
     * Клиент для работы с API
     * @var AmoCRMApiClient
     */
    private AmoCRMApiClient $apiClient;

    public function __construct(AmoCRMApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $state = bin2hex(random_bytes(16));

        //Генерации ссылки с параметрами на страницу авторизации
        $authorizationUrl = $this->apiClient->getOAuthClient()->getAuthorizeUrl([
            'state' => $state,
            'mode' => 'post_message', //post_message - редирект произойдет в открытом окне, popup - редирект произойдет в окне родителе
        ]);

        return new RedirectResponse($authorizationUrl);
    }
}
