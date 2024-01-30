<?php

declare(strict_types=1);

namespace DataBase\Handler;

use DataBase\Services\Interfaces\CreateUserInterface;
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
     * @var CreateUserInterface
     */
    private CreateUserInterface $createUser;

    public function __construct(CreateUserInterface $createUser)
    {
        $this->createUser = $createUser;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        $user = $this->createUser->create(
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
