<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AmoClient\Interfaces\GetAmoCRMApiClientInterface;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use DataBase\Services\ApiToken\create\Interfaces\SaveApiTokenInterface;
use DataBase\Services\Integration\Get\Interfaces\GetIntegrationInterface;
use DataBase\Services\User\create\Interfaces\SaveUserInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

/**
 * Класс обработчика отвественного за получение и обработку данных
 * авторизации полученных от AmoCRM. /amo_redirect_uri
 */
class AmoRedirectUriApiHandler implements RequestHandlerInterface
{
    /**
     * @var GetIntegrationInterface
     */
    private GetIntegrationInterface $getIntegration;
    /**
     * @var GetAmoCRMApiClientInterface
     */
    private GetAmoCRMApiClientInterface $getAmoCRMApiClient;
    /**
     * @var SaveUserInterface
     */
    private SaveUserInterface $saveUser;
    /**
     * @var SaveApiTokenInterface
     */
    private SaveApiTokenInterface $saveApiToken;

    public function __construct(
        GetIntegrationInterface $getIntegration,
        GetAmoCRMApiClientInterface $getAmoCRMApiClient,
        SaveUserInterface $saveUser,
        SaveApiTokenInterface $saveApiToken
    ) {
        $this->getIntegration = $getIntegration;
        $this->getAmoCRMApiClient = $getAmoCRMApiClient;
        $this->saveUser = $saveUser;
        $this->saveApiToken = $saveApiToken;
    }

    public function handle(
        ServerRequestInterface $request
    ): ResponseInterface {

        try {

            $queryParams = $request->getQueryParams();
            /** Сохранения AccessToken после установки интеграции */
            if ($queryParams['from_widget']) {

                $integration = $this->getIntegration->get(
                    'client_id',
                    $queryParams['client_id']
                );

                if ($integration === null) {
                    throw new AmoCRMoAuthApiException(
                        'client_id неверный или незарегистри́рованный'
                    );
                }

                $apiClient = $this->getAmoCRMApiClient
                    ->getAmoClient($integration->id);

                $apiClient->setAccountBaseDomain($queryParams['referer']);

                $accessToken = $apiClient->getOAuthClient()
                    ->getAccessTokenByCode($queryParams['code']);

                $resourceOwner = $apiClient->getOAuthClient()
                    ->getResourceOwner($accessToken);

                $user = $this->saveUser->save($resourceOwner->getId());

                $user->integrations()->attach($integration->id);

                $apiToken = $this->saveApiToken->save(
                    $accessToken->getToken(),
                    $accessToken->getExpires(),
                    $accessToken->getRefreshToken(),
                    $queryParams['referer'],
                    $user->id
                );

                return new JsonResponse($resourceOwner->toArray());
            }

            /** Сохранение AccessToken при ручной авторизации */
            if (array_key_exists($queryParams['state'], $_SESSION['oauth2list'])) {
                $integrationId = $_SESSION['oauth2list']['state'];

                $apiClient = $this->getAmoCRMApiClient
                    ->getAmoClient($integrationId);

                $apiClient->setAccountBaseDomain($queryParams['referer']);

                $accessToken = $apiClient->getOAuthClient()
                    ->getAccessTokenByCode($queryParams['code']);

                $resourceOwner = $apiClient->getOAuthClient()
                    ->getResourceOwner($accessToken);

                $user = $this->saveUser->save($resourceOwner->getId());

                $user->integrations()->attach($integrationId);

                $apiToken = $this->saveApiToken->save(
                    $accessToken->getToken(),
                    $accessToken->getExpires(),
                    $accessToken->getRefreshToken(),
                    $queryParams['referer'],
                    $user->id
                );

                return new JsonResponse($resourceOwner->toArray());

            }

            throw new AmoCRMoAuthApiException(
                'Не хватает данных для сохранения AccessToken.'
            );
        } catch (AmoCRMoAuthApiException $ex) {

            $responce = [
                'Error' => [
                    'Exception' => get_class($ex),
                    'Message' => $ex->getMessage(),
                ]
            ];

            return new JsonResponse($responce);

        } catch (Throwable $throwable) {
            return new JsonResponse('Упс, что-то пошло не так.');
        }
    }
}
