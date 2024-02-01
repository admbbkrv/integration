<?php

declare(strict_types=1);

namespace DataBase\Services\Integration\Create;

use DataBase\Models\Integration;
use DataBase\Services\Integration\Create\Interfaces\SaveIntegrationInterface;

class SaveIntegrationService implements SaveIntegrationInterface
{
    /**
     * @inheritDoc
     */
    public function save(
        string $clientId,
        string $clientSecret,
        string $redirectUri
    ): Integration {
        return Integration::query()->updateOrCreate(
            [
                'client_id' => $clientId,
            ],
            [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri,
            ]
        );
    }
}
