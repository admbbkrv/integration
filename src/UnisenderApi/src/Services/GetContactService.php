<?php

namespace UnisenderApi\Services;

use Exception;
use Unisender\ApiWrapper\UnisenderApi;

/**
 * Сервис для получения контакта из Unisender
 */
class GetContactService implements GetContactInterface
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getContact(UnisenderApi $unisenderApi, string $email): array
    {
       $params = [
           'email' => $email,
       ];

       $response = $unisenderApi->getContact($params);

       $responseArray = json_decode($response, true);

       if ($responseArray['error']) {
           throw new Exception('Ошибка: ' . $responseArray['error'] . ' ' . 'Код ошибки: ' . $responseArray['code']);
       }

       return $responseArray;
    }
}
