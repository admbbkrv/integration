<?php

declare(strict_types=1);

namespace DataBase\Handler;

use AmoApiClient\Services\AmoClient\Interfaces\GetAmoCRMApiClientInterface;
use AmoApiClient\Services\AmoClient\Webhooks\Interfaces\GenerateWebhookModelInterface;
use AmoApiClient\Services\ContactServices\Interfaces\FilterWithEmailInterface;
use AmoApiClient\Services\ContactServices\Interfaces\GetAllContactsInterface;
use DataBase\Services\ApiToken\create\Interfaces\SaveApiKeyInterface;
use DataBase\Services\ApiToken\get\Interfaces\GetAccessTokenInterface;
use DataBase\Services\User\get\Interfaces\GetUserInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use UnisenderApi\Services\ImportContactsInterface;
use UnisenderApi\Services\PrepareForImportInterface;
use UnisenderApi\Services\UnisenderApi\Interfaces\GetUnisenderApiInterface;

/**
 * Обработчик запросов сохранения api key
 */
class SaveApiKeyHandler implements RequestHandlerInterface
{
    private GetUserInterface $getUser;
    private SaveApiKeyInterface $saveApiKey;
    private GetAmoCRMApiClientInterface $getAmoClient;
    private RouterInterface $router;
    private GenerateWebhookModelInterface $webhookModel;
    private GetAccessTokenInterface $getAccessToken;
    private GetUnisenderApiInterface $getUnisenderApi;
    private GetAllContactsInterface $getContactsService;
    private FilterWithEmailInterface $filterWithEmailService;
    private PrepareForImportInterface $prepareForImportService;
    private ImportContactsInterface $importContactsService;

    public function __construct(
        GetUserInterface $getUser,
        SaveApiKeyInterface $saveApiKey,
        GetAmoCRMApiClientInterface $getAmoClient,
        RouterInterface $router,
        GenerateWebhookModelInterface $webhookModel,
        GetAccessTokenInterface $getAccessToken,
        GetUnisenderApiInterface $getUnisenderApi,
        GetAllContactsInterface $getContactsService,
        FilterWithEmailInterface $filterWithEmailService,
        PrepareForImportInterface $prepareForImportService,
        ImportContactsInterface $importContactsService
    ) {
        $this->getUser = $getUser;
        $this->saveApiKey = $saveApiKey;
        $this->getAmoClient = $getAmoClient;
        $this->router = $router;
        $this->webhookModel = $webhookModel;
        $this->getAccessToken = $getAccessToken;
        $this->getUnisenderApi = $getUnisenderApi;
        $this->getContactsService = $getContactsService;
        $this->filterWithEmailService = $filterWithEmailService;
        $this->prepareForImportService = $prepareForImportService;
        $this->importContactsService = $importContactsService;
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

        $unisenderApi = $this->getUnisenderApi->get($postParams['api_key']);

        $integrationId = $user->integrations()->first()->id;
        $apiToken = $user->apiToken()->first();
        $accessToken = $this->getAccessToken
            ->getAccessToken($apiToken->base_domain);

        $apiClient = $this->getAmoClient->getAmoClient($integrationId);

        $apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($apiToken->base_domain);

        //Первичная синхронизация
        $contactsCollection = $this->getContactsService
            ->getContacts($apiClient);

        $contactsCollectionWithEmail = $this->filterWithEmailService
            ->filterWithEmail($contactsCollection);

        $preparedContacts = $this->prepareForImportService
            ->prepare($contactsCollectionWithEmail);

        $this->importContactsService
            ->importContacts($preparedContacts, $unisenderApi);



        $domain = getenv('SERVICE_DOMAIN');
        $route = $this->router
            ->generateUri('api.amo.contacts.unis.webhook');
        $params = '?integration_id=' . $integrationId;
        $destination = $domain . $route . $params;

        $settings = [
            'add_contact',
            'update_contact',
            'delete_contact',
        ];

        $webhookModel = $this->webhookModel->generate($settings, $destination);

        $webhookResponse = $apiClient->webhooks()
            ->subscribe($webhookModel)->toArray();

        $response = [
            'successfully_created' => [
                [
                    'created' => 'api_key',
                    'account_id' => $postParams['account_id']
                ],
                [
                    'created' => 'webhook',
                    'id' => $webhookResponse['id']
                ]
            ]
        ];

        return new JsonResponse($response);
    }
}
