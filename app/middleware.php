<?php

use App\Application\Middleware\AppMiddleware;
use App\Application\Middleware\CsRfGuard;
use App\Application\Middleware\SessionMiddleware;
use App\Application\Middleware\TwigMiddleware;
use Slim\App;

if (!isset($app)) {
    return;
}
if (!$app instanceof App){
    return;
}

$app->add(TwigMiddleware::createFromContainer($app));

$app->add(SessionMiddleware::class);

$app->add(CsRfGuard::class);

$app->add(AppMiddleware::class);
