<?php
declare(strict_types=1);

namespace App\Application\Middleware;


use Slim\App;

class BootMiddleware
{
    public static function setup(App $app)
    {
        $app->add(TwigMiddleware::createFromContainer($app));

        $app->add(SessionMiddleware::class);

        $app->add(CsRfGuard::class);

        $app->add(AppMiddleware::class);

    }
}