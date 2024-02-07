<?php

declare(strict_types=1);

namespace Beanstalkd\Commands\Workers;


use Beanstalkd\config\BeanstalkConfig;
use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Job;
use Pheanstalk\Pheanstalk;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

abstract class BaseWorker extends Command
{
    /** @var Pheanstalk Текущее подключение к серверу очередей. */
    protected Pheanstalk $connection;

    /** @var string Просматриваемая очередь */
    protected string $queue = 'default';

    /**
     * Constructor BaseWorker
     * @param BeanstalkConfig $beanstalk
     */
    final protected function __construct(BeanstalkConfig $beanstalk)
    {
        $this->connection = $beanstalk->getConnection();
        parent::__construct();
    }

    /** Вызов через CLI
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        while ($job = $this->connection
            ->watchOnly($this->queue)
            ->ignore(PheanstalkInterface::DEFAULT_TUBE)
            ->reserve()
        ) {
            try {
                $this->process(json_decode(
                    $job->getData(),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                ));
            } catch (Throwable $exception) {
                $this->handleException($exception, $job);
            }

            $this->connection->delete($job);
        }
    }

    /**
     * @param Throwable $exception
     * @param Job $job
     */
    private function handleException(Throwable $exception, Job $job): void
    {
        echo "Error Unhandled exception $exception" . PHP_EOL . $job->getData();
    }

    /** Обработка задачи. */
    abstract protected function process($data);

    /** Статический метод, где определяются заисимости класса
     * и возвразается объект класса
     * @param ContainerInterface$container
     * @return BaseWorker.
     */
    abstract public static function getObject(ContainerInterface $container): BaseWorker;
}