<?php
declare(strict_types=1);

namespace App\Application\Handlers;


use App\Application\Middleware\SessionMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

class BootHandlers
{
    /**
     * @param App $app
     * @param ServerRequestInterface $request
     */
    public static function setup(App $app, ServerRequestInterface $request)
    {
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

    }
}