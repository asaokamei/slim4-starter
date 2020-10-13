<?php
namespace App\Application;

use App\Application\Middleware\SessionMiddleware;
use BadMethodCallException;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Views\TwigMiddleware;

if (!isset($app)) {
    return;
}
if (!$app instanceof App){
    return;
}

/**
 * set up middleware
 */

// Register middleware
$app->add(TwigMiddleware::createFromContainer($app));

$app->add(SessionMiddleware::class);

$responseFactory = $app->getResponseFactory();

// Register Middleware To Be Executed On All Routes
$storage = null;
$app->add(new Guard($responseFactory,
    '_csrf',
    $storage,
    null,
    200,
    16,
    true));
