<?php

declare(strict_types=1);

namespace DataBase\Handler;

use DataBase\Services\ApiToken\create\Interfaces\SaveApiKeyInterface;
use DataBase\Services\User\get\Interfaces\GetUserInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Обработчик запросов сохранения api key
 */
class SaveApiKeyHandler implements RequestHandlerInterface
{
    private GetUserInterface $getUser;
    private SaveApiKeyInterface $saveApiKey;

    public function __construct(
        GetUserInterface $getUser,
        SaveApiKeyInterface $saveApiKey
    ) {
        $this->getUser = $getUser;
        $this->saveApiKey = $saveApiKey;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $postParams = $request->getParsedBody();

        if (!$postParams['account_id']) {
            $message = 'You have not passed the account_id';
            $response = [
                'Error' => [
                    'Message' => $message
                ],
            ];

            return new JsonResponse($response);
        }

        $user = $this->getUser
            ->get('account_id', $postParams['account_id']);

        if ($user === null) {
            $message = 'Your user(account) is not registered. Please complete the initial authorization.';
            $response = [
                'Error' => [
                    'Message' => $message
                ],
            ];

            return new JsonResponse($response);
        }

        $this->saveApiKey->saveApiKey(
            $postParams['api_key'],
            $user->id
        );

        $response = [
            'successfully_created' => [
                [
                    'created' => 'api_key',
                    'account_id' => $postParams['account_id']
                ]
            ]
        ];

        return new JsonResponse($response);
    }
}
