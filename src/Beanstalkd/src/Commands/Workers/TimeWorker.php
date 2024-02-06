<?php

declare(strict_types=1);

namespace Beanstalkd\Commands\Workers;

use Carbon\Carbon;
use Pheanstalk\Pheanstalk;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Класс для вывода времени по command 'how-time'
 */
class TimeWorker extends Command
{
    /** @var string Просматриваемая очередь */
    public const QUEUE = 'times';

    /** @var string */
    protected static $defaultName = 'times:worker';

    protected function configure(): void
    {
        $this->setName(self::$defaultName);
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $pheanstalk = Pheanstalk::create('127.0.0.1', 11300)
            ->watch(self::QUEUE);

        while (true) {
            $job = $pheanstalk->reserve();
            $data = $job->getData();

            if ($data === 'show-time') {
                $now = Carbon::now();
                $timeString = $now->format('H:i (m.Y)');
                $output->writeln("Now time: $timeString");
            }

            $pheanstalk->delete($job);
        }

        return Command::SUCCESS;
    }
}
