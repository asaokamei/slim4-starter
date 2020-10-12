<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface;
use App\Application\Handlers\HttpErrorHandler;
use App\Application\Handlers\ShutdownHandler;
use App\Application\Middleware\SessionMiddleware;
use Slim\App;


if (!isset($app) || !isset($request)) {
    throw new BadMethodCallException();
}
if (!$app instanceof App || !$request instanceof ServerRequestInterface){
    throw new BadMethodCallException();
}

/**
 * set up error handlers
 */
$app->add(SessionMiddleware::class);

/** @var bool $displayErrorDetails */
$displayErrorDetails = $app->getContainer()->get('settings')['displayErrorDetails'];

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$callableResolver = $app->getCallableResolver();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory, $app->getContainer()->get('view'));

// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

