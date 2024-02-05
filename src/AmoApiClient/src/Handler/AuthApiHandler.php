<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AmoClient\Interfaces\GetAmoCRMApiClientInterface;
use DataBase\Services\Integration\Get\Interfaces\GetIntegrationInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Класс обработчка, который отвественен за страницу авторизации API AmoCRN /auth
 */
class AuthApiHandler implements RequestHandlerInterface
{
    private GetAmoCRMApiClientInterface $getAmoCRMApiClient;
    private GetIntegrationInterface $getIntegration;

    public function __construct(
        GetIntegrationInterface $getIntegration,
        GetAmoCRMApiClientInterface $getAmoCRMApiClient
    ) {
        $this->getAmoCRMApiClient = $getAmoCRMApiClient;
        $this->getIntegration = $getIntegration;
    }

    public function handle(
        ServerRequestInterface $request
    ): ResponseInterface {

        if (!session_status()) {
            session_start();
        }

        $postParams = $request->getParsedBody();

        if (!$postParams['client_id'] && !$postParams['client_secret']) {
            $message = 'You have not passed the client_id or client_secret';
            $response = [
                'Error' => [
                    'Message' => $message
                ],
            ];

            return new JsonResponse($response);
        }

        $integration = $this->getIntegration
            ->get('client_id', $postParams['client_id']);

        if ($integration === null) {
            $message = 'Your integration is not registered. Please register your integration.';
            $response = [
                'Error' => [
                    'Message' => $message
                ],
            ];

            return new JsonResponse($response);
        }

        if ($integration->client_secret != $postParams['client_secret']) {
            $message = 'You entered an invalid client_secret';
            $response = [
                'Error' => [
                    'Message' => $message
                ],
            ];

            return new JsonResponse($response);
        }
        $integration_id = $integration->id;

        $state = bin2hex(random_bytes(16));

        $_SESSION['oauth2list'] = [
            $state => $integration_id
        ];

        $apiClient = $this->getAmoCRMApiClient
            ->getAmoClient((int) $integration_id);

        $authorizationUrl = $apiClient->getOAuthClient()->getAuthorizeUrl([
            'state' => $state,
            'mode' => 'post_message',
        ]);

        return new RedirectResponse($authorizationUrl);
    }
}
