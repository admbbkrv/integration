<?php

declare(strict_types=1);

namespace UnisenderApi\Handler;

use AmoApiClient\Constants\AmoApiConstants;
use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoApiClient\Services\ContactServices\Interfaces\FilterWithEmailInterface;
use AmoApiClient\Services\ContactServices\Interfaces\GetAllContactsInterface;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Unisender\ApiWrapper\UnisenderApi;
use UnisenderApi\Services\ImportContactsInterface;
use UnisenderApi\Services\PrepareForImportInterface;

/**
 * Обработчик ответсвенный за запрос импорта контактов в Unisender
 */
class ImportContactsUnisHandler implements RequestHandlerInterface
{
    /**
     * @var AmoCRMApiClient
     */
    private AmoCRMApiClient $apiClient;
    /**
     * @var UnisenderApi
     */
    private UnisenderApi $unisenderApi;
    /**
     * @var GetAllContactsInterface
     */
    private GetAllContactsInterface $getContactsService;
    /**
     * @var FilterWithEmailInterface
     */
    private FilterWithEmailInterface $filterWithEmailService;
    /**
     * @var PrepareForImportInterface
     */
    private PrepareForImportInterface $prepareForImportService;
    /**
     * @var GetTokenInterface
     */
    private GetTokenInterface $getTokenService;
    /**
     * @var ImportContactsInterface
     */
    private ImportContactsInterface $importContactsService;
    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    public function __construct(
        AmoCRMApiClient $apiClient,
        UnisenderApi $unisenderApi,
        GetAllContactsInterface $getContactsService,
        FilterWithEmailInterface $filterWithEmailService,
        PrepareForImportInterface $prepareForImportService,
        GetTokenInterface $getTokenService,
        ImportContactsInterface $importContactsService,
        RouterInterface $router
    ) {
        $this->apiClient = $apiClient;
        $this->unisenderApi = $unisenderApi;
        $this->getContactsService = $getContactsService;
        $this->filterWithEmailService = $filterWithEmailService;
        $this->prepareForImportService = $prepareForImportService;
        $this->getTokenService = $getTokenService;
        $this->importContactsService = $importContactsService;
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

        try {

            $this->apiClient->setAccessToken($accessToken)->setAccountBaseDomain($accessToken->getValues()['baseDomain']);

            $contactsCollection = $this->getContactsService->getContacts($this->apiClient);

            $contactsCollectionWithEmail = $this->filterWithEmailService->filterWithEmail($contactsCollection);

            $preparedContacts = $this->prepareForImportService->prepare($contactsCollectionWithEmail);

            $responce = $this->importContactsService->importContacts($preparedContacts, $this->unisenderApi);

        } catch (AmoCRMApiNoContentException $exception) {

            $response = [
                'error' => [
                    'exception' => get_class($exception),
                    'error_code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ]
            ];

            return new JsonResponse($response);

        } catch (\Throwable $throwable) {

            $response = [
                'error' => [
                    'message' => 'Упс, что-то пошло не так',
                ]
            ];

            return new JsonResponse($response);
        }

        return new JsonResponse($responce);
    }
}
