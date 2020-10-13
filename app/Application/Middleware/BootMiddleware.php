<?php


namespace App\Application\Middleware;


use Slim\App;
use Slim\Csrf\Guard;

class BootMiddleware
{
    public static function setup(App $app)
    {
        // Register middleware
        $app->add(TwigMiddleware::createFromContainer($app));

        $app->add(SessionMiddleware::class);

        $responseFactory = $app->getResponseFactory();

        // Register Middleware To Be Executed On All Routes
        $guard = new Guard($responseFactory, '_csrf');
        $guard->setPersistentTokenMode(true);
        $app->add($guard);
    }
}