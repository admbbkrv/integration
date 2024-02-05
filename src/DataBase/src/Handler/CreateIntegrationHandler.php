<?php

declare(strict_types=1);

namespace DataBase\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Обработчик страницы
 * с формой создания интеграции
 */
class CreateIntegrationHandler implements RequestHandlerInterface
{
    public function handle(
        ServerRequestInterface $request
    ): ResponseInterface {

        $formHtml = <<<HTML
            <!DOCTYPE html>
            <html lang="ru">
            <head>
                <meta charset="UTF-8">
                <meta 
                    name="viewport" 
                    content="width=device-width, initial-scale=1.0"
                >
                <title>Регистрация интеграции</title>
            </head>
            <body>
                <form action="/db/integration/save" method="post">
                    <div>
                        <label for="client_id">Client ID:</label>
                        <input 
                            type="text" 
                            id="client_id" 
                            name="client_id" 
                            required
                        >
                    </div>
                    <div>
                        <label for="client_secret">Client Secret:</label>
                        <input 
                            type="text" 
                            id="client_secret" 
                            name="client_secret" 
                            required
                        >
                    </div>
                    <div>
                        <label for="redirect_uri">Redirect URI:</label>
                        <input 
                            type="text" 
                            id="redirect_uri" 
                            name="redirect_uri" 
                            required
                         >
                    </div>
                    <button type="submit">Отправить</button>
                </form>
            </body>
            </html>
            HTML;

        return new HtmlResponse($formHtml);
    }
}
