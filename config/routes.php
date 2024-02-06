<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

/**
 * FastRoute route configuration
 *
 * @see https://github.com/nikic/FastRoute
 *
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/{id:\d+}', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Mezzio\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */

return static function (
    Application $app,
    MiddlewareFactory $factory,
    ContainerInterface $container
): void {
    $app->get(
        '/api/auth',
        \AmoApiClient\Handler\AuthApiTemlateHandler::class,
        'api.auth'
    );
    $app->post(
        '/api/auth/get_auth_uri',
        \AmoApiClient\Handler\AuthApiHandler::class,
        'api.auth.get_auth_uri'
    );

    // Пути для получения и сохранения токенов
    $app->get(
        '/api/save_token/amo_token',
        \AmoApiClient\Handler\AmoRedirectUriApiHandler::class,
        'api.save_token.amo_token'
    );
    $app->post(
        '/api/save_token/unis_token',
        \DataBase\Handler\SaveApiKeyHandler::class,
        'amo.save_token.unis_token'
    );

    //Пути для работы API AmoCRM
    $app->get(
        '/amo/main',
        \AmoApiClient\Handler\MainApiHandler::class,
        'amo.main'
    );
    $app->get(
        '/amo/contacts',
        \AmoApiClient\Handler\ContactsApiHandler::class,
        'amo.contacts'
    );
    $app->get(
        '/amo/unis/contacts/import',
        \UnisenderApi\Handler\ImportContactsUnisHandler::class,
        'amo.unis.contacts.import'
    );

    $app->post(
        '/api/amo/contacts/unis/webhook',
        \AmoApiClient\Handler\UnisWebhookHandler::class,
        'api.amo.contacts.unis.webhook'
    );

    //Пути для работы API Unisender
    $app->get(
        '/unis/contact',
        \UnisenderApi\Handler\GetContactUnisApiHandler::class,
        'unis.contact'
    );

    //пути для работы с БД
    $app->get(
        '/db/user/create',
        \DataBase\Handler\CreateUserHandler::class,
        'db.user.create'
    );
    $app->get(
        '/db/integration/create',
        \DataBase\Handler\CreateIntegrationHandler::class,
        'db.integration.create'
    );
    $app->post(
        '/db/integration/save',
        \DataBase\Handler\SaveIntegrationHandler::class,
        'db.integration.save'
    );

    //пути отображения форм
};
