<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use DataBase\Services\ApiToken\get\GetApiTokenService;
use DataBase\Services\Contact\create\Interfaces\SaveContactInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use UnisenderApi\Services\PrepareData\Interfaces\GetPreparedContactsForDeleteInterface;
use UnisenderApi\Services\PrepareData\Interfaces\GetPreparedContactsForImportInterface;
use UnisenderApi\Services\PrepareData\Interfaces\GetPreparedContactsForUpdateInterface;
use UnisenderApi\Services\UnisenderApi\Interfaces\GetUnisenderApiInterface;

/**
 * Обработчик хуков с контактами
 */
class UnisWebhookHandler implements RequestHandlerInterface
{
    private GetPreparedContactsForImportInterface $contactsForImport;
    private GetApiTokenService $getApiToken;
    private GetUnisenderApiInterface $getUnisenderApi;
    private GetPreparedContactsForDeleteInterface $contactsForDelete;
    private SaveContactInterface $saveContact;
    private GetPreparedContactsForUpdateInterface $contactsForUpdate;

    public function __construct(
        GetPreparedContactsForImportInterface $contactsForImport,
        GetPreparedContactsForDeleteInterface $contactsForDelete,
        GetPreparedContactsForUpdateInterface $contactsForUpdate,
        GetApiTokenService $getApiToken,
        GetUnisenderApiInterface $getUnisenderApi,
        SaveContactInterface $saveContact
    ) {
        $this->contactsForImport = $contactsForImport;
        $this->getApiToken = $getApiToken;
        $this->getUnisenderApi = $getUnisenderApi;
        $this->contactsForDelete = $contactsForDelete;
        $this->saveContact = $saveContact;
        $this->contactsForUpdate = $contactsForUpdate;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $postParams = $request->getParsedBody();
        $baseDomain = $postParams['account']['subdomain'] . '.amocrm.ru';
        $apiKey = $this->getApiToken
            ->get('base_domain', $baseDomain)->api_key;

        $unisenderApi= $this->getUnisenderApi->get($apiKey);

        //При добавлении контактов
        if ($postParams['contacts']['add']) {
            $preparedContacts = $this->contactsForImport
                ->prepare($postParams['contacts']['add'][0]);
            $contact = $this->saveContact
                ->save((int) $preparedContacts['bd']['contact_id']);
            $contact->emails()
                ->createMany($preparedContacts['bd']['emails']);
        }

        //При обновлении контактов
        if ($postParams['contacts']['update']) {
           $preparedContacts = $this->contactsForUpdate
               ->prepare($postParams['contacts']['update'][0]);
           $contact = $preparedContacts['bd']['contact_model'];

            // Добавляем новые email адреса
            $contact->emails()
                ->createMany($preparedContacts['bd']['emails']['create']);

            // Удаляем отсутствующие email адреса
            $emailsToDelete = $preparedContacts['bd']['emails']['delete'];
            foreach ($emailsToDelete as $emailToDelete) {
                $contact->emails()->where('email', $emailToDelete)->delete();
            }
        }


        if ($postParams['contacts']['delete']) {

            $preparedContacts = $this->contactsForDelete
                ->prepare($postParams['contacts']['delete'][0]);

            $contact = $preparedContacts['bd']['contact_model'];
            $contact->delete();
        }

        $response = $unisenderApi->importContacts($preparedContacts['unis']);


        return new JsonResponse($response, 200);
    }
}
