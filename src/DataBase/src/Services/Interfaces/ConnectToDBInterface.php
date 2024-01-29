<?php

declare(strict_types=1);

namespace DataBase\Services\Interfaces;

/**
 * Интерфейс подключения к базе данных
 */
interface ConnectToDBInterface
{
    /**
     * Метод создаия подключения к базе данных.
     * Возвращает массив с данными о версии базы данных
     * @param array $config
     * @return array
     */
    public function connect(array $config): array;
}
