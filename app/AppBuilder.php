<?php

namespace App;

use App\Application\Container\BootContainer;
use App\Application\Container\BootEnv;
use Exception;
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

        $app = $this->middleware($app);

        $app = $this->routes($app);

        if ($request) {
            $app = $this->errors($app, $request);
        }

        return $app;
    }

    private function routes(App $app): App
    {
        require __DIR__ . '/Application/routes.php';

        return $app;
    }

    private function middleware(App $app): App
    {
        require __DIR__ . '/Application/middleware.php';

        return $app;
    }

    private function errors(App $app, ServerRequestInterface $request): App
    {
        require __DIR__ . '/Application/errors.php';

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
        $app = AppFactory::create();
        $container->set(App::class, $app); // register $app self.

        return $app;
    }

}