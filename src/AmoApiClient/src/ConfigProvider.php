<?php

declare(strict_types=1);

namespace AmoApiClient;


use AmoApiClient\Handler\AmoRedirectUriApiHandler;
use AmoApiClient\Handler\AmoRedirectUriApiHandlerFactory;
use AmoApiClient\Handler\AuthApiHandler;
use AmoApiClient\Handler\AuthApiHandlerFactory;
use AmoApiClient\Handler\AuthApiTemlateHandler;
use AmoApiClient\Handler\AuthApiTemlateHandlerFactory;
use AmoApiClient\Handler\ContactsApiHandler;
use AmoApiClient\Handler\ContactsApiHandlerFactory;
use AmoApiClient\Handler\MainApiHandler;
use AmoApiClient\Handler\MainApiHandlerFactory;
use AmoApiClient\Handler\UnisWebhookHandler;
use AmoApiClient\Handler\UnisWebhookHandlerFactory;
use AmoApiClient\Middleware\ApiAmoAuthMiddleware;
use AmoApiClient\Middleware\ApiAmoAuthMiddlewareFactory;
use AmoApiClient\Middleware\DotEnvMiddleware;
use AmoApiClient\Middleware\DotEnvMiddlewareFactory;
use AmoApiClient\Services\AccessTokenService\AccessTokenService;
use AmoApiClient\Services\AccessTokenService\AccessTokenServiceFactory;
use AmoApiClient\Services\AccessTokenService\GetTokenInterface;
use AmoApiClient\Services\AccessTokenService\GetTokenService;
use AmoApiClient\Services\AccessTokenService\InitAccessTokenFromApiTokenService;
use AmoApiClient\Services\AccessTokenService\InitAccessTokenFromModelInterface;
use AmoApiClient\Services\AccessTokenService\SaveTokenInterface;
use AmoApiClient\Services\AccessTokenService\SaveTokenService;
use AmoApiClient\Services\AmoClient\GetAmoCRMApiClientService;
use AmoApiClient\Services\AmoClient\Interfaces\GetAmoCRMApiClientInterface;
use AmoApiClient\Services\AmoClient\Webhooks\GenerateWebhookModelService;
use AmoApiClient\Services\AmoClient\Webhooks\Interfaces\GenerateWebhookModelInterface;
use AmoApiClient\Services\ContactServices\ContactService;
use AmoApiClient\Services\ContactServices\FilterWithEmailService;
use AmoApiClient\Services\ContactServices\GetAllContactsService;
use AmoApiClient\Services\ContactServices\Interfaces\FilterWithEmailInterface;
use AmoApiClient\Services\ContactServices\Interfaces\GetAllContactsInterface;
use AmoApiClient\Services\ContactServices\Interfaces\GetNamesWithEmailsInterface;
use AmoApiClient\Services\OAuth\OAuthConfig;
use AmoApiClient\Services\OAuth\OAuthService;
use AmoCRM\OAuth\OAuthConfigInterface;
use AmoCRM\OAuth\OAuthServiceInterface;
use Beanstalkd\Commands\Workers\GetTokensExpireSoonCommand;
use Beanstalkd\Producers\TokensProducer;
use DataBase\Handler\SaveIntegrationHandler;
use DataBase\Handler\SaveIntegrationHandlerFactory;
use DataBase\Services\ApiToken\get\Interfaces\GetExpireSoonTokensInterface;
use DataBase\Services\Interfaces\ConnectToDBInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;

/**
 * The configuration provider for the AmoApiClient module
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
                'tokens:expire-soon' => GetTokensExpireSoonCommand::class,
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
                SaveTokenInterface::class => SaveTokenService::class,
                GetTokenInterface::class => GetTokenService::class,
                OAuthServiceInterface::class => OAuthService::class,
                OAuthConfigInterface::class => OAuthConfig::class,
                Dotenv::class => Dotenv::class,
                GetNamesWithEmailsInterface::class => ContactService::class,
                GetAllContactsInterface::class => GetAllContactsService::class,
                FilterWithEmailInterface::class => FilterWithEmailService::class,
                GetAmoCRMApiClientInterface::class => GetAmoCRMApiClientService::class,
                GenerateWebhookModelInterface::class => GenerateWebhookModelService::class,
                InitAccessTokenFromModelInterface::class => InitAccessTokenFromApiTokenService::class,
            ],
            'factories'  => [
                MainApiHandler::class => MainApiHandlerFactory::class,
                ContactsApiHandler::class => ContactsApiHandlerFactory::class,
                AccessTokenService::class => AccessTokenServiceFactory::class,
                ApiAmoAuthMiddleware::class => ApiAmoAuthMiddlewareFactory::class,
                AuthApiHandler::class => AuthApiHandlerFactory::class,
                AmoRedirectUriApiHandler::class => AmoRedirectUriApiHandlerFactory::class,
                DotEnvMiddleware::class => DotEnvMiddlewareFactory::class,
                SaveIntegrationHandler::class => SaveIntegrationHandlerFactory::class,
                AuthApiTemlateHandler::class => AuthApiTemlateHandlerFactory::class,
                UnisWebhookHandler::class => UnisWebhookHandlerFactory::class,
                //Commands
                GetTokensExpireSoonCommand::class => function (ContainerInterface $container) {
                    return new GetTokensExpireSoonCommand(
                        $container->get(ConnectToDBInterface::class),
                        $container->get(GetExpireSoonTokensInterface::class),
                        $container->get(TokensProducer::class),
                        $container->get('config')['database']
                    );
                },
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
                'amo-api-client'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}
