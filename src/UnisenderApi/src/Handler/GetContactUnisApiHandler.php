<?php

declare(strict_types=1);

namespace UnisenderApi\Handler;

use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use Unisender\ApiWrapper\UnisenderApi;
use UnisenderApi\Services\GetContactInterface;

/**
 * Обработчик ответсвтенный за запрос получения контакта
 * с сервиса Unisender
 */
class GetContactUnisApiHandler implements RequestHandlerInterface
{
    /**
     * Сервис для работы с API Unisender
     * @var UnisenderApi
     */
    private UnisenderApi $unisenderApi;
    /**
     * Интерфейс получения контакта из Unisender
     * @var GetContactInterface
     */
    private GetContactInterface $getContactService;

    public function __construct(
        UnisenderApi $unisenderApi,
        GetContactInterface $getContactService
    ) {
        $this->unisenderApi = $unisenderApi;
        $this->getContactService = $getContactService;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        try {
            if ($queryParams['email']) {
                $response = $this->getContactService->getContact(
                    $this->unisenderApi,
                    $queryParams['email']
                );
                return new JsonResponse($response);
            }

            throw new Exception('В запросе не передан email для получения контакта');
        } catch (Throwable $throwable) {
            exit($throwable->getMessage());
        }
    }
}
