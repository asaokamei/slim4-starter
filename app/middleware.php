<?php
declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Views\TwigMiddleware;

return function (App $app) {

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
};
