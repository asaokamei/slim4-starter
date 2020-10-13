<?php


namespace App\Application\Middleware;


use Slim\App;
use Slim\Csrf\Guard;

class BootMiddleware
{
    public static function setup(App $app)
    {
        $app->add(TwigMiddleware::createFromContainer($app));

        $app->add(SessionMiddleware::class);

        $app->add(Guard::class);
    }
}