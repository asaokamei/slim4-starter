<?php


namespace App\Application\Middleware;


use Slim\App;
use Slim\Csrf\Guard;
use Slim\Views\TwigMiddleware;

class BootMiddleware
{
    public static function setup(App $app)
    {
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
    }
}