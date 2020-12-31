<?php
declare(strict_types=1);

namespace App;

use App\Application\Container\BootContainer;
use App\Application\Container\BootEnv;
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

    public function setUseCache($useCache): AppBuilder
    {
        $this->useCache = $useCache;
        return $this;
    }

    /**
     * @param ServerRequestInterface|null $request
     * @return App
     * @throws Exception
     */
    public function build(ServerRequestInterface $request = null): App
    {
        $app = $this->makeApp();

        $this->middleware($app);
        $this->routes($app);
        $this->setup($app, $request);

        return $app;
    }

    /** @noinspection PhpUnusedParameterInspection */
    private function middleware(App $app)
    {
        require __DIR__ . '/middleware.php';
    }

    /** @noinspection PhpUnusedParameterInspection */
    private function routes(App $app)
    {
        require __DIR__ . '/routes.php';
    }

    /** @noinspection PhpUnusedParameterInspection */
    private function setup(App $app, ServerRequestInterface $request = null)
    {
        require __DIR__ . '/setup.php';
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