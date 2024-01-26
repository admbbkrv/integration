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

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/auth', \AmoApiClient\Handler\AuthApiHandler::class, 'amo_auth');
    $app->get('/amo_redirect_uri', \AmoApiClient\Handler\RedirectUriApiHandler::class, 'amo_redirect_uri');

    //Пути для работы API AmoCRM
    $app->get('/amo/main', \AmoApiClient\Handler\MainApiHandler::class, 'amo.main');
    $app->get('/amo/contacts', \AmoApiClient\Handler\ContactsApiHandler::class, 'amo.contacts');
    $app->get('/amo/unis/contacts/import', \UnisenderApi\Handler\ImportContactsUnisHandler::class, 'amo.unis.contacts.import');

    //Пути для работы API Unisender
    $app->get('/unis/contact', \UnisenderApi\Handler\GetContactUnisApiHandler::class, 'unis.contact');
};
