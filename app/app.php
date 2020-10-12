<?php
declare(strict_types=1);

use App\Application\Container\BootContainer;
use App\Application\Container\BootEnv;
use Slim\App;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

/**
 * @param bool $useCache
 * @param bool $showError
 * @return App
 */
return function ($useCache = false, $showError = true)
{
    $projectRoot = realpath(__DIR__.'/../');
    $cacheRoot = $projectRoot . '/var/cache/';

    // Set up settings

    $env = BootEnv::forge($projectRoot, $cacheRoot)
        ->setUseCache($useCache);
    $defaults = [
        'projectRoot' => $projectRoot,
        'cacheDirectory' => $cacheRoot,
        'production' => $env->isProduction(),
        'displayErrorDetails' => $showError,
    ];
    $settings = $defaults + $env->load();

    // Build PHP-DI Container instance

    $container = BootContainer::forge($settings)
        ->setUseCache($useCache)
        ->build();

    // Instantiate the app
    AppFactory::setContainer($container);
    return $app = AppFactory::create();
};