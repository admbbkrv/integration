<?php

declare(strict_types=1);

namespace DataBase\Services\ApiToken\create;

use DataBase\Models\ApiToken;
use DataBase\Services\ApiToken\create\Interfaces\SaveApiKeyInterface;

class SaveApiKeyService extends SaveApiTokenService implements
    SaveApiKeyInterface
{
    /**
     * @inheritDoc
     */
    public function saveApiKey(
        string $apiKey,
        int $user_id
    ): ApiToken {
        $values = [
            'api_key' => $apiKey,
        ];

        return $this->save(
            $user_id,
            $values
        );
    }
}
