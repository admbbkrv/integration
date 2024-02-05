<?php

declare(strict_types=1);

namespace AmoApiClient\Middleware;

use DataBase\Services\ApiToken\get\Interfaces\GetAccessTokenInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Класс middleware для проверки наличия токена авторизации
 */
class ApiAmoAuthMiddleware implements MiddlewareInterface
{
    /**
     * @var GetAccessTokenInterface
     */
    private GetAccessTokenInterface $getAccessToken;

    public function __construct(
        GetAccessTokenInterface $getAccessToken,
    ) {
        $this->getAccessToken = $getAccessToken;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ) : ResponseInterface {

        $queryParams = $request->getQueryParams();

        if (!$queryParams['integration_id']) {
            $message = 'You have not passed the integration_id';
            $response = [
                'Error' => [
                    'Message' => $message
                ],
            ];

            return new JsonResponse($response);
        }

        $postParams = $request->getParsedBody();
        $baseDomain = $postParams['subdomain'] . '.amocrm.ru';
        $accessToken = $this->getAccessToken->getAccessToken($baseDomain);

        if ($accessToken === null) {
            $integrationData = 'integration_id=' . $queryParams['integration_id'];
            $message = 'You dont have ApiToken for authorization integration('
                . $integrationData
                . '). Complete the initial authorization';
            $response = [
                'Error' => [
                    'Message' => $message
                ],
            ];

            return new JsonResponse($response);
        }

        return $handler->handle($request);
    }
}
