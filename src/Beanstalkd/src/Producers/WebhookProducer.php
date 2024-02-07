<?php

declare(strict_types=1);

namespace Beanstalkd\Producers;

use Beanstalkd\config\BeanstalkConfig;
use Pheanstalk\Pheanstalk;

/**
 * Продусер отправки сообщения c данными вебхука
 */
class WebhookProducer
{
    /** @var Pheanstalk Текущее подключение к серверу очередей. */
    protected Pheanstalk $connection;

    public function __construct(BeanstalkConfig $beanstalk)
    {
        $this->connection = $beanstalk->getConnection();
    }

    /**
     * Отправка сообщение в сервер очереди
     * @return void
     */
    public function produce(array $data): void
    {
        $this->connection
            ->useTube('webhooks')
            ->put(json_encode($data));

    }
}
