<?php

namespace Beanstalkd\Commands\Workers;

use Beanstalkd\config\BeanstalkConfig;
use DataBase\Services\ApiToken\get\GetApiTokenService;
use DataBase\Services\Contact\create\Interfaces\SaveContactInterface;
use DataBase\Services\Interfaces\ConnectToDBInterface;
use DataBase\Services\User\get\Interfaces\GetUserInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;
use UnisenderApi\Services\PrepareData\GetPreparedContactsForAddService;
use UnisenderApi\Services\PrepareData\GetPreparedContactsForDeleteService;
use UnisenderApi\Services\PrepareData\GetPreparedContactsForUpdateService;
use Illuminate\Database\Capsule\Manager as Capsule;
use UnisenderApi\Services\UnisenderApi\Interfaces\GetUnisenderApiInterface;

class WebhookWorker extends BaseWorker
{
    /** @var string Просматриваемая очередь */
    protected string $queue = 'webhooks';
    private GetPreparedContactsForAddService $addService;
    private GetPreparedContactsForUpdateService  $updateService;
    private GetPreparedContactsForDeleteService $deleteService;
    private GetApiTokenService $getApiToken;
    private GetUnisenderApiInterface $getUnisenderApi;
    private GetUserInterface $getUser;
    private SaveContactInterface $saveContact;
    private ConnectToDBInterface $connectToDB;
    private array $dbConfig;





    /**
     * @inheritDoc
     */
    public function process($data)
    {
        $this->connectToDB->connect($this->dbConfig);

        $baseDomain = $data['account']['subdomain'] . '.amocrm.ru';

        $apiKey = $this->getApiToken
            ->get('base_domain', $baseDomain)->api_key;

        $unisenderApi = $this->getUnisenderApi->get($apiKey);

        //При добавлении контактов
        if ($data['contacts']['add']) {
            $preparedContacts = $this->addService
                ->prepare($data['contacts']['add'][0]);

            $user = $this->getUser
                ->get('account_id', (int) $data['account']['id']);

            $contact = $this->saveContact
                ->save(
                    (int) $preparedContacts['bd']['contact_id'],
                    $user->id,
                );

            $contact->emails()
                ->createMany($preparedContacts['bd']['emails']);
        }


        //При обновлении контактов
        if ($data['contacts']['update']) {
            $preparedContacts = $this->updateService
                ->prepare($data);

            if ($preparedContacts === null) {
                return;
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

        //При удалении контактов
        if ($data['contacts']['delete']) {

            $preparedContacts = $this->deleteService
                ->prepare($data);

            $contact = $preparedContacts['bd']['contact_model'];
            $contact->delete();
        }

        $unisenderApi->importContacts($preparedContacts['unis']);
    }

    /**
     * @inheritDoc
     */
    public static function getObject(
        ContainerInterface $container
    ): WebhookWorker {
        $instance = new self($container->get(BeanstalkConfig::class));

        $instance->setAddService(
            $container->get(GetPreparedContactsForAddService::class)
        );

        $instance->setUpdateService(
            $container->get(GetPreparedContactsForUpdateService::class)
        );

        $instance->setDeleteService(
            $container->get(GetPreparedContactsForDeleteService::class)
        );

        $instance->setGetApiTokenService(
            $container->get(GetApiTokenService::class)
        );

        $instance->setGetUnisenderApiService(
            $container->get(GetUnisenderApiInterface::class)
        );

        $instance->setGetUser(
            $container->get(GetUserInterface::class)
        );

        $instance->setSaveContactService(
            $container->get(SaveContactInterface::class)
        );

        $instance->setConnectToDBService(
            $container->get(ConnectToDBInterface::class)
        );

        $instance->setDbConfig(
            $container->get('config')['database']
        );

        return $instance;
    }

    /**
     * Сеттер для свойства $addService
     * @param GetPreparedContactsForAddService $service
     * @return void
     */
    private function setAddService(
        GetPreparedContactsForAddService $service
    ): void {
        $this->addService = $service;
    }

    /**
     * Сеттер для свойства $updateService
     * @param GetPreparedContactsForUpdateService $service
     * @return void
     */
    private function setUpdateService(
        GetPreparedContactsForUpdateService $service
    ): void {
        $this->updateService = $service;
    }

    /**
     * Сеттер для свойства $deleteService
     * @param GetPreparedContactsForDeleteService $service
     * @return void
     */
    private function setDeleteService(
        GetPreparedContactsForDeleteService $service
    ): void {
        $this->deleteService = $service;
    }

    /**
     * Сеттер для свойства $getApiToken
     * @param GetApiTokenService $service
     * @return void
     */
    private function setGetApiTokenService(
        GetApiTokenService $service
    ): void {
        $this->getApiToken = $service;
    }

    /**
     * Сеттер для свойства $getUnisenderApi
     * @param GetUnisenderApiInterface $service
     * @return void
     */
    private function setGetUnisenderApiService(
        GetUnisenderApiInterface $service
    ): void {
        $this->getUnisenderApi = $service;
    }

    /**
     * Сеттер для свойства $getUser
     * @param GetUserInterface $service
     * @return void
     */
    private function setGetUser(
        GetUserInterface $service
    ): void
    {
        $this->getUser = $service;
    }

    /**
     * Сеттер для свойства $saveContact
     * @param SaveContactInterface $service
     * @return void
     */
    private function setSaveContactService(
        SaveContactInterface $service
    ): void {
        $this->saveContact= $service;
    }

    /**
     * Сеттер для свойства $connectToDB
     * @param ConnectToDBInterface $service
     * @return void
     */
    private function setConnectToDBService(
        ConnectToDBInterface $service
    ): void {
        $this->connectToDB= $service;
    }

    /**
     * Сеттер для свойства $dbConfig
     * @param array $config
     * @return void
     */
    private function setDbConfig(
        array $config
    ): void {
        $this->dbConfig = $config;
    }
}