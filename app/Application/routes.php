<?php
declare(strict_types=1);

use App\Controllers\Samples\FormController;
use App\Controllers\Samples\WelcomeController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

if (!isset($app)) {
    throw new BadMethodCallException();
}
if (!$app instanceof App){
    throw new BadMethodCallException();
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
