<?php

namespace AmoApiClient\Services\AmoClient\Webhooks;

use AmoApiClient\Services\AmoClient\Webhooks\Interfaces\GenerateWebhookModelInterface;
use AmoCRM\Models\WebhookModel;

/**
 * Сервис для генереации \AmoCRM\Models\WebhookModel
 */
class GenerateWebhookModelService implements GenerateWebhookModelInterface
{
    /**
     * @inheritDoc
     */
    public function generate(
        array $settings,
        string $destination
    ): WebhookModel {
        return (new WebhookModel())
            ->setSettings($settings)
            ->setDestination($destination);
    }
}
