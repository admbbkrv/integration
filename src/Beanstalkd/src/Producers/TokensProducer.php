<?php

declare(strict_types=1);

namespace Beanstalkd\Producers;

use Beanstalkd\config\BeanstalkConfig;
use Illuminate\Database\Eloquent\Collection;
use Pheanstalk\Pheanstalk;

/**
 * Продусер отправки сообщения c данными вебхука
 */
class TokensProducer
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
    public function produce(Collection $collection): void
    {
        $this->connection
            ->useTube('tokens')
            ->put(json_encode($collection));
    }
}
