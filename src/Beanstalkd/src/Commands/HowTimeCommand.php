<?php

declare(strict_types=1);

namespace Beanstalkd\Commands;

use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Класс для вывода времени по command 'how-time'
 */
class HowTimeCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'how-time';

    protected function configure(): void
    {
        $this->setName(self::$defaultName);
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {

        $now = Carbon::now();
        $timeString = $now->format('H:i (m.Y)');

        $output->writeln("Now time: $timeString");

        return Command::SUCCESS;
    }
}
