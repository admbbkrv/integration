<?php

declare(strict_types=1);

namespace Beanstalkd\config;

use Pheanstalk\Pheanstalk;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class BeanstalkConfig
{
    /** @var Pheanstalk|null Подключение к серверу очередей */
    private ?Pheanstalk $connection;

    /** @var array Конфигурация подключения */
    private array $config;

    /**
     * Constructor Beanstalk
     */
    public function __construct(ContainerInterface $container)
    {
        try {
            $this->config = $container->get('config')['beanstalk'];
            $this->connection = Pheanstalk::create(
                $this->config['host'],
                $this->config['port'],
                $this->config['timeout']
            );
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            exit($e->getMessage());
        }
    }

    /**
     * Возвращает подключение к серверу очередей.
     * @return Pheanstalk|null
     */
    public function getConnection(): ?Pheanstalk
    {
        return $this->connection;
    }
}
