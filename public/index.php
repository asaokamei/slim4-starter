<?php
declare(strict_types=1);

use Slim\App;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\ResponseEmitter;

session_start();

/** @var App $app */
$production = false;
$appBuilder = include __DIR__ . '/../app/app.php';
$app = $appBuilder($production);

$callableResolver = $app->getCallableResolver();

// Register middleware
$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Handle errors
$errors = require __DIR__ . '/../app/errors.php';
$errors($app, $request);

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
