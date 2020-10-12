<?php
declare(strict_types=1);

use Slim\App;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\ResponseEmitter;

if (php_sapi_name() == 'cli-server') {
    /* 静的コンテンツのルーティングをして false を返します */
    $path = $_SERVER["REQUEST_URI"];
    if (is_dir($path)) goto SERVER;
    if (file_exists($path)) return false;
    if ($path === '/worker.js') return false;
}
SERVER:

session_start();

/** @var App $app */
$useCache = false;
$showError = true;
$appBuilder = include __DIR__ . '/../app/app.php';
$app = $appBuilder($useCache, $showError);

$callableResolver = $app->getCallableResolver();

// Register middleware
$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

// Create Request object from globals
$request = ServerRequestCreatorFactory::create()->createServerRequestFromGlobals();

// Handle errors
$errors = require __DIR__ . '/../app/errors.php';
$errors($app, $request);

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
