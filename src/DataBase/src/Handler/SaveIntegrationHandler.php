<?php

declare(strict_types=1);

namespace DataBase\Handler;

use DataBase\Services\Integration\Create\Interfaces\SaveIntegrationInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Обработчик запросов сохранения данных интеграции
 */
class SaveIntegrationHandler implements RequestHandlerInterface
{
    /**
     * @var SaveIntegrationInterface
     */
    private SaveIntegrationInterface $saveIntegration;

    public function __construct(
      SaveIntegrationInterface $saveIntegration
    ) {
        $this->saveIntegration = $saveIntegration;
    }

    public function handle(
        ServerRequestInterface $request
    ): ResponseInterface {

        $data = $request->getParsedBody();

        $integration = $this->saveIntegration->save(
            $data['client_id'],
            $data['client_secret'],
            $data['redirect_uri'],
        );

        $responce = [
            'successfully created' => [
                'integration_id' => $integration->id
            ]
        ];

        return new JsonResponse($responce);
    }
}
