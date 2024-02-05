<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Services\AmoClient\Interfaces\GetAmoCRMApiClientInterface;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use DataBase\Services\ApiToken\create\Interfaces\SaveAccessTokenInterface;
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
     * @var SaveAccessTokenInterface
     */
    private SaveAccessTokenInterface $saveAccessToken;


    public function __construct(
        GetIntegrationInterface $getIntegration,
        GetAmoCRMApiClientInterface $getAmoCRMApiClient,
        SaveUserInterface $saveUser,
        SaveAccessTokenInterface $saveAccessToken
    ) {
        $this->getIntegration = $getIntegration;
        $this->getAmoCRMApiClient = $getAmoCRMApiClient;
        $this->saveUser = $saveUser;
        $this->saveAccessToken = $saveAccessToken;
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

                $accountDomain = $apiClient->getOAuthClient()
                    ->getAccountDomain($accessToken);

                $user = $this->saveUser->save($accountDomain->getId());

                $user->integrations()->syncWithoutDetaching(
                    [$integration->id]
                );

                $apiToken = $this->saveAccessToken->saveAccessToken(
                    $accessToken->getToken(),
                    $accessToken->getExpires(),
                    $accessToken->getRefreshToken(),
                    $queryParams['referer'],
                    $user->id
                );

                return new JsonResponse($accountDomain->toArray());
            }

            /** Сохранение AccessToken при ручной авторизации */
            if (array_key_exists($queryParams['state'], $_SESSION['oauth2list'])) {
                $integrationId = $_SESSION['oauth2list']['state'];

                $apiClient = $this->getAmoCRMApiClient
                    ->getAmoClient($integrationId);

                $apiClient->setAccountBaseDomain($queryParams['referer']);

                $accessToken = $apiClient->getOAuthClient()
                    ->getAccessTokenByCode($queryParams['code']);

                $accountDomain = $apiClient->getOAuthClient()
                    ->getAccountDomain($accessToken);

                $user = $this->saveUser->save($accountDomain->getId());

                $user->integrations()->attach($integrationId);

                $apiToken = $this->saveAccessToken->saveAccessToken(
                    $accessToken->getToken(),
                    $accessToken->getExpires(),
                    $accessToken->getRefreshToken(),
                    $queryParams['referer'],
                    $user->id
                );

                return new JsonResponse($accountDomain->toArray());

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
