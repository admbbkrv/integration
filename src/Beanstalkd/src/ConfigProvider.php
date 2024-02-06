<?php

declare(strict_types=1);

namespace Beanstalkd;

use Beanstalkd\Commands\HowTimeCommand;

/**
 * The configuration provider for the Beanstalkd module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke() : array
    {
        return [
            'laminas-cli' => $this->getCliConfig(),
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the CLI commands
     */
    public function getCliConfig() : array
    {
        return [
            'commands' => [
                'how-time' => HowTimeCommand::class,
            ],
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [
                HowTimeCommand::class => HowTimeCommand::class,
            ],
            'factories'  => [
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'beanstalkd'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}
