<?php

declare(strict_types=1);

namespace AmoApiClient\Services\AmoClient\Webhooks\Interfaces;

use AmoCRM\Models\WebhookModel;

/**
 * Интерфейс для генереации \AmoCRM\Models\WebhookModel
 */
interface GenerateWebhookModelInterface
{
    /**
     * Возвращает объект WebhookModel
     * @param array $settings
     * @param string $destination
     * @return WebhookModel
     */
    public function generate(
        array $settings,
        string $destination
    ): WebhookModel;
}
