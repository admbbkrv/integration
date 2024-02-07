<?php

declare(strict_types=1);

namespace Beanstalkd\Producers;

use Pheanstalk\Pheanstalk;

/**
 * Продусер отправки сообщения получения времени
 */
class TimeProducer
{
    /**
     * Отправка сообщение в сервер очереди
     * @return void
     */
    public function produce(): void
    {
        Pheanstalk::create('127.0.0.1', 11300)
            ->useTube('times')
            ->put('show-time');

    }
}
