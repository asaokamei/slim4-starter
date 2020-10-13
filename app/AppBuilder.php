<?php
declare(strict_types=1);

namespace App;

use App\Application\Container\BootContainer;
use App\Application\Container\BootEnv;
use App\Application\Handlers\BootHandlers;
use App\Application\Middleware\BootMiddleware;
use Exception;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Factory\AppFactory;

class AppBuilder
{
    /**
     * @var bool
     */
    private $useCache = false;

    /**
     * @var bool
     */
    private $showError = false;

    /**
     * @var string
     */
    private $root;

    /**
     * @var string
     */
    private $cache;

    public function __construct(string $root, string $cache)
    {
        $this->root = $root;
        $this->cache = $cache;
    }

    public static function forge(string $root, string $cache = null): self
    {
        $cache = $cache ?? $root . '/var/cache/';
        return new self($root, $cache);
    }

    public function setShowError(bool $showError): AppBuilder
    {
        $this->showError = $showError;
        return $this;
    }

    public function setUseCache($useCache)
    {
        $this->useCache = $useCache;
        return $this;
    }

    public function build(ServerRequestInterface $request = null): App
    {
        $app = $this->makeApp();

        BootMiddleware::setup($app);

        $this->routes($app);

        if ($request) {
            BootHandlers::setup($app, $request);
        }

        return $app;
    }

    private function routes(App $app): App
    {
        require __DIR__ . '/Application/routes.php';

        return $app;
    }

    /**
     * @return App
     * @throws Exception
     */
    private function makeApp(): App
    {
        // Set up settings

        $env = BootEnv::forge($this->root, $this->cache)
            ->setUseCache($this->useCache);
        $defaults = [
            'projectRoot' => $this->root,
            'cacheDirectory' => $this->cache,
            'production' => $env->isProduction(),
            'displayErrorDetails' => $this->showError,
        ];
        $settings = $defaults + $env->load();

        // Build PHP-DI Container instance

        $container = BootContainer::forge($settings)
            ->setUseCache($this->useCache)
            ->build();

        // Instantiate the app
        AppFactory::setContainer($container);
        AppFactory::setResponseFactory($container->get(ResponseFactoryInterface::class));

        $app = AppFactory::create();
        $container->set(App::class, $app); // register $app self.

        return $app;
    }

}