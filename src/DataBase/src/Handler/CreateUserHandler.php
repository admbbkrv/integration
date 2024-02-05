<?php

declare(strict_types=1);

namespace DataBase\Handler;

use DataBase\Services\User\create\Interfaces\SaveUserInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Обработчик отвественный за запрос создания пользователя
 */
class CreateUserHandler implements RequestHandlerInterface
{
    /**
     * @var SaveUserInterface
     */
    private SaveUserInterface $createUser;

    public function __construct(SaveUserInterface $createUser)
    {
        $this->createUser = $createUser;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        $user = $this->createUser->save(
            $queryParams['email'],
            $queryParams['password']
        );

        $responce = [
            'user' => [
                'email' => $user->user_email,
            ]
        ];

        return new JsonResponse($responce);
    }
}
