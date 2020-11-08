<?php
declare(strict_types=1);

use App\Controllers\Samples\FlashController;
use App\Controllers\Samples\FormController;
use App\Controllers\Samples\WelcomeController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

if (!isset($app)) {
    return;
}
if (!$app instanceof App){
    return;
}

/**
 * set up routes
 */
$app->get('/', function (Request $request, Response $response) {
    return $this->get('view')->render($response, 'hello.twig');
})->setName('hello');

/**
 * sample groups. 
 */
$app->group('/samples', function (Group $group) {
    $group->any('/form', FormController::class)->setName('form');
    $group->any('/welcome/{name}', WelcomeController::class)->setName('welcome');
});

/**
 * flash samples
 */
$app->group('/flashes', function (Group $group) {
    $group->get('/', FlashController::class)->setName('flash');
    $group->get('/{method}', FlashController::class)->setName('flashes');
});