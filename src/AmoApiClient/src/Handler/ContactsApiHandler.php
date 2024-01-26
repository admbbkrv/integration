<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Constants\AmoApiConstants;
use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoApiClient\Services\ContactServices\Interfaces\GetNamesWithEmailsInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Класс обработчика для запросов получения контактов
 */
class ContactsApiHandler implements RequestHandlerInterface
{
    /**
     * @var AmoCRMApiClient
     */
    private AmoCRMApiClient $apiClient;
    /**
     * @var GetTokenInterface
     */
    private GetTokenInterface $getTokenService;
    /**
     * @var RouterInterface
     */
    private RouterInterface $router;
    /**
     * @var GetNamesWithEmailsInterface
     */
    private GetNamesWithEmailsInterface $contactService;

    public function __construct(
        AmoCRMApiClient $apiClient,
        GetTokenInterface $getTokenService,
        GetNamesWithEmailsInterface $contactService,
        RouterInterface $router
    ) {
        $this->apiClient = $apiClient;
        $this->getTokenService = $getTokenService;
        $this->contactService = $contactService;
        $this->router = $router;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $accessToken = $this->getTokenService->get(AmoApiConstants::TOKEN_FILE);

        if ($accessToken->hasExpired()) {
            try {
                $accessToken = $this->apiClient->getOAuthClient()->getAccessTokenByRefreshToken($accessToken->getRefreshToken());
            } catch (AmoCRMoAuthApiException $e) {

                $uri = $this->router->generateUri('amo_auth');

                return new RedirectResponse($uri);
            }
        }
        $this->apiClient->setAccessToken($accessToken)->setAccountBaseDomain($accessToken->getValues()['baseDomain']);
        $contacts = $this->apiClient->contacts()->get();
        $contactsNamesAndEmailsArray = $this->contactService->getNamesWithEmails($contacts);

        return new JsonResponse($contactsNamesAndEmailsArray);
    }
}
