<?php

declare(strict_types=1);

namespace Beanstalkd\Commands\Workers;

use Beanstalkd\Producers\TokensProducer;
use DataBase\Services\ApiToken\get\Interfaces\GetExpireSoonTokensInterface;
use DataBase\Services\Interfaces\ConnectToDBInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Класс для получения токенов, которые
 * истекают в ближайшие 24 часа
 */
class GetTokensExpireSoonCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'tokens:expire-soon';

    private ConnectToDBInterface $connectToDB;

    private GetExpireSoonTokensInterface $tokens;

    /** @var array Конфигурация БД */
    private array $dbConfig;
    private TokensProducer $producer;

    public function __construct(
      ConnectToDBInterface $connectToDB,
      GetExpireSoonTokensInterface $tokens,
      TokensProducer $producer,
      array $dbConfig
    ) {
        $this->connectToDB = $connectToDB;
        $this->tokens = $tokens;
        $this->dbConfig = $dbConfig;
        parent::__construct();
        $this->producer = $producer;
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $this->connectToDB->connect($this->dbConfig);

        $tokens = $this->tokens->get();

        $this->producer->produce($tokens);

        return Command::SUCCESS;
    }
}
