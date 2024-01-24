<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoCRM\Client\AmoCRMApiClient;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Random\RandomException;

/**
 * Класс обработчка, который отвественен за страницу авторизации API AmoCRN /auth
 */
class AuthApiHandler implements RequestHandlerInterface
{
    /**
     * Объект API клиента
     * @var AmoCRMApiClient
     */
    private AmoCRMApiClient $apiClient;

    public function __construct(AmoCRMApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        if (!session_status()) {
            session_start();
        }

        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth2state'] = $state;

        $authorizationUrl = $this->apiClient->getOAuthClient()->getAuthorizeUrl([
            'state' => $state,
            'mode' => 'post_message',
        ]);

        return new RedirectResponse($authorizationUrl);
    }
}
