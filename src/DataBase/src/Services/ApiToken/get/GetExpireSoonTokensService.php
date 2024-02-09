<?php

declare(strict_types=1);

namespace DataBase\Services\ApiToken\get;

use DataBase\Models\ApiToken;
use DataBase\Services\ApiToken\get\Interfaces\GetExpireSoonTokensInterface;
use Illuminate\Database\Eloquent\Collection;

/** Сервис получения истекающих токенов */
class GetExpireSoonTokensService implements GetExpireSoonTokensInterface
{
    /**
     * @inheritDoc
     */
    public function get(): Collection
    {
        $currentTime = time();
        $timesAfter24Hours = $currentTime + (24 * 60 * 60);

        return ApiToken::query()
            ->whereBetween('expires', [$currentTime, $timesAfter24Hours])
            ->get();
    }
}
