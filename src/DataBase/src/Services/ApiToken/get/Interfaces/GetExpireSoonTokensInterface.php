<?php

declare(strict_types=1);

namespace DataBase\Services\ApiToken\get\Interfaces;

use Illuminate\Database\Eloquent\Collection;

/** Интерфейс для получение истекающих токенов */
interface GetExpireSoonTokensInterface
{
    /** Возвращает истекающие токены
     * @return Collection
     */
    public function get(): Collection;
}
