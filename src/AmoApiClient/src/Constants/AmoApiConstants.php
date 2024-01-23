<?php

declare(strict_types=1);

namespace AmoApiClient\Constants;

/**
 * Класс хранит константы для работы API клинета
 */
class AmoApiConstants
{
    /**
     * Путь к Access Token файлу
     */
    public const TOKEN_FILE = DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'token_info.json';
}