<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use DataBase\Services\ApiToken\get\GetApiTokenService;
use DataBase\Services\Contact\create\Interfaces\SaveContactInterface;
use DataBase\Services\User\get\Interfaces\GetUserInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use UnisenderApi\Services\PrepareData\GetPreparedContactsForAddService;
use UnisenderApi\Services\PrepareData\GetPreparedContactsForDeleteService;
use UnisenderApi\Services\PrepareData\GetPreparedContactsForUpdateService;
use UnisenderApi\Services\UnisenderApi\Interfaces\GetUnisenderApiInterface;

/**
 * Обработчик хуков с контактами
 */
class UnisWebhookHandler implements RequestHandlerInterface
{
    private GetPreparedContactsForAddService $contactsForImport;
    private GetApiTokenService $getApiToken;
    private GetUnisenderApiInterface $getUnisenderApi;
    private GetPreparedContactsForDeleteService $contactsForDelete;
    private SaveContactInterface $saveContact;
    private GetPreparedContactsForUpdateService $contactsForUpdate;
    private GetUserInterface $getUser;

    public function __construct(
        GetPreparedContactsForAddService $contactsForImport,
        GetPreparedContactsForDeleteService $contactsForDelete,
        GetPreparedContactsForUpdateService $contactsForUpdate,
        GetApiTokenService $getApiToken,
        GetUnisenderApiInterface $getUnisenderApi,
        SaveContactInterface $saveContact,
        GetUserInterface $getUser
    ) {
        $this->contactsForImport = $contactsForImport;
        $this->getApiToken = $getApiToken;
        $this->getUnisenderApi = $getUnisenderApi;
        $this->contactsForDelete = $contactsForDelete;
        $this->saveContact = $saveContact;
        $this->contactsForUpdate = $contactsForUpdate;
        $this->getUser = $getUser;
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
            $user = $this->getUser
                ->get('account_id', (int) $postParams['account']['id']);

            $contact = $this->saveContact
                ->save(
                    (int) $preparedContacts['bd']['contact_id'],
                    $user->id,
                );

            $contact->emails()
                ->createMany($preparedContacts['bd']['emails']);
        }

        //При обновлении контактов
        if ($postParams['contacts']['update']) {
           $preparedContacts = $this->contactsForUpdate
               ->prepare($postParams);
           if ($preparedContacts === null) {
               return new JsonResponse('Contacts updated', 200);
           }
           $contact = $preparedContacts['bd']['contact_model'];

            // Добавляем новые email адреса
            if ($preparedContacts['bd']['emails']['create']) {
                $contact->emails()
                    ->createMany($preparedContacts['bd']['emails']['create']);
            }

            // Удаляем отсутствующие email адреса
            if ($preparedContacts['bd']['emails']['delete']) {
                $emailsToDelete = $preparedContacts['bd']['emails']['delete'];
                foreach ($emailsToDelete as $emailToDelete) {
                    $contact->emails()->where('email', $emailToDelete)->delete();
                }
            }
        }


        if ($postParams['contacts']['delete']) {

            $preparedContacts = $this->contactsForDelete
                ->prepare($postParams);

            $contact = $preparedContacts['bd']['contact_model'];
            $contact->delete();
        }

        $response = $unisenderApi->importContacts($preparedContacts['unis']);

        return new JsonResponse($response, 200);
    }
}
