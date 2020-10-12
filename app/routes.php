<?php
declare(strict_types=1);

use App\Controllers\Forms\FormController;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        return $this->get('view')->render($response, 'hello.twig');
    })->setName('hello');

    $app->any('/form', FormController::class)->setName('form');
};
