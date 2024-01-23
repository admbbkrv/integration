<?php

declare(strict_types=1);

namespace AmoApiClient\Handler;

use AmoApiClient\Constants\AmoApiConstants;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Filters\ContactsFilter;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ContactsApiHandler extends ApiHandler implements RequestHandlerInterface
{
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

        $filter = new ContactsFilter();
        $contacts = $this->apiClient->setAccessToken($accessToken)->contacts()->get();


        $contactsArray = [];
        foreach ($contacts as $contact) {

            $customFields = $contact->getCustomFieldsValues();
            $contactEmailField = $customFields->getBy('fieldCode', 'EMAIL');
            $email = $contactEmailField
                ? $contactEmailField->getValues()->current()->getValue()
                : null;

            $contactsArray[] = [
                'name' => $contact->getName(),
                'email' => $email,
            ];
        }

        return new JsonResponse($contactsArray);
    }
}
