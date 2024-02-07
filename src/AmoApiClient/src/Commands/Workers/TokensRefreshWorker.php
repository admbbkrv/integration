<?php

namespace AmoApiClient\Commands\Workers;

use AmoApiClient\Services\AccessTokenService\InitAccessTokenFromModelInterface;
use AmoApiClient\Services\AmoClient\Interfaces\GetAmoCRMApiClientInterface;
use Beanstalkd\Commands\Workers\BaseWorker;
use Beanstalkd\config\BeanstalkConfig;
use DataBase\Models\ApiToken;
use DataBase\Models\User;
use DataBase\Services\Interfaces\ConnectToDBInterface;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Container\ContainerInterface;

/** Worker обновляющий токены с истекающим сроком */
class TokensRefreshWorker extends BaseWorker
{
    /** @var string Просматриваемая очередь */
    protected string $queue = 'tokens';

    private ConnectToDBInterface $connectToDB;
    private GetAmoCRMApiClientInterface $getApiClient;
    private InitAccessTokenFromModelInterface $initAccessToken;

    /** @var array Конфигурация БД */
    private array $dbConfig;

    /**
     * @inheritDoc
     */
    public function process($data)
    {
        $this->connectToDB->connect($this->dbConfig);

        /** @var ApiToken $apiToken  */
        foreach ($data as $apiToken) {

            $accessToken = $this->initAccessToken->init($apiToken);

            /** @var User $user  */
            $user = $apiToken->user()->first();

            $integrationId = $user->integrations()->first()->id;

            $apiClient = $this->getApiClient->getAmoClient($integrationId);

            $apiClient->getOAuthClient()
                ->setAccessTokenRefreshCallback(
                    function (AccessToken $token, string $baseDomain) use ($apiToken) {
                        $apiToken->access_token = $token->getToken();
                        $apiToken->expires = $token->getToken();
                        $apiToken->refresh_token = $token->getToken();
                        $apiToken->save();
                })
                ->getAccessTokenByRefreshToken($accessToken->getRefreshToken());
        }

    }

    /**
     * @inheritDoc
     */
    public static function getObject(
        ContainerInterface $container
    ): TokensRefreshWorker {

        $instance = new self($container->get(BeanstalkConfig::class));

        $instance->setConnectToDBService(
            $container->get(ConnectToDBInterface::class)
        );

        $instance->setGetApiClient(
            $container->get(GetAmoCRMApiClientInterface::class)
        );

        $instance->setGetApiClient(
            $container->get(InitAccessTokenFromModelInterface::class)
        );

        $instance->setDbConfig(
            $container->get('config')['database']
        );

        return $instance;
    }

    /**
     * Сеттер для свойства $connectToDB
     * @param ConnectToDBInterface $service
     * @return void
     */
    private function setConnectToDBService(
        ConnectToDBInterface $service
    ): void {
        $this->connectToDB = $service;
    }

    /**
     * Сеттер для свойства $getApiClient
     * @param GetAmoCRMApiClientInterface $service
     * @return void
     */
    private function setGetApiClient(
        GetAmoCRMApiClientInterface $service
    ): void {
        $this->getApiClient = $service;
    }

    /**
     * Сеттер для свойства $initAccessToken
     * @param InitAccessTokenFromModelInterface $service
     * @return void
     */
    private function setInitAccessToken(
        InitAccessTokenFromModelInterface $service
    ): void {
        $this->initAccessToken = $service;
    }

    /**
     * Сеттер для свойства $dbConfig
     * @param array $config
     * @return void
     */
    private function setDbConfig(
        array $config
    ): void {
        $this->dbConfig = $config;
    }
}
