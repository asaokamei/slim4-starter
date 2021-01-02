<?php


use App\Application\Handlers\HttpErrorHandler;
use App\Application\Handlers\ShutdownHandler;
use App\Application\Session\SessionInterface;
use App\Application\Twig\TwigExtension;
use App\Application\Twig\TwigRuntimeLoader;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Views\Twig;

if (!isset($app) || !isset($request)) {
    return;
}
if (!$app instanceof App){
    return;
}
if (!$request instanceof ServerRequestInterface){
    return;
}

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

/**
 * set up Twig extension
 */
$twig = $app->getContainer()->get(Twig::class);

$runtimeLoader = new TwigRuntimeLoader($app->getRouteCollector()->getRouteParser(), $request, $app->getContainer()->get(SessionInterface::class), $app->getBasePath());
$twig->addRuntimeLoader($runtimeLoader);

$extension = new TwigExtension();
$twig->addExtension($extension);
