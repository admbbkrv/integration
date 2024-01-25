<?php

declare(strict_types=1);

namespace UnisenderApi\Handler;

use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;
use UnisenderApi\Handler\Traits\GetUnisenderApiServiceTrait;
use UnisenderApi\Services\GetContactInterface;

/**
 * Обработчик ответсвтенный за запрос получения контакта
 * с сервиса Unisender
 */
class GetContactUnisApiHandler implements RequestHandlerInterface
{
    use GetUnisenderApiServiceTrait;

    /**
     * Интерфейс получения контакта из Unisender
     * @var GetContactInterface
     */
    private GetContactInterface $getContactService;

    public function __construct(
        GetContactInterface $getContactService
    ) {
        $this->getContactService = $getContactService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $unisenderApi = $this->getUnisenderApi();
        try {
            if ($queryParams['email']) {
                $response = $this->getContactService->getContact(
                    $unisenderApi,
                    $queryParams['email']
                );
                return new JsonResponse($response);
            }

            throw new Exception('В запросе не передан email для получения контакта');
        } catch (Throwable $throwable) {
            $response = [
                'error' => $throwable->getMessage(),
            ];

            return new JsonResponse($response);
        }
    }
}
