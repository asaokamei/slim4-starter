<?php
/**
 * Slim Framework (http://slimframework.com)
 *
 * @license   https://github.com/slimphp/Twig-View/blob/master/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Application\Twig\TwigExtension;
use App\Application\Twig\TwigRuntimeLoader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;

class TwigMiddleware implements MiddlewareInterface
{
    /**
     * @var Twig
     */
    protected $twig;

    /**
     * @var RouteParserInterface
     */
    protected $routeParser;

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var string|null
     */
    protected $attributeName;

    /**
     * @param App    $app
     * @param string $containerKey
     *
     * @return TwigMiddleware
     */
    public static function createFromContainer(App $app, string $containerKey = 'view'): self
    {
        $container = $app->getContainer();
        $twig = $container->get($containerKey);

        return new self(
            $twig,
            $app->getRouteCollector()->getRouteParser(),
            $app->getBasePath()
        );
    }

    /**
     * @param Twig                 $twig
     * @param RouteParserInterface $routeParser
     * @param string               $basePath
     * @param string|null          $attributeName
     */
    public function __construct(
        Twig $twig,
        RouteParserInterface $routeParser,
        string $basePath = '',
        ?string $attributeName = null
    ) {
        $this->twig = $twig;
        $this->routeParser = $routeParser;
        $this->basePath = $basePath;
        $this->attributeName = $attributeName;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $runtimeLoader = new TwigRuntimeLoader($this->routeParser, $request, $this->basePath);
        $this->twig->addRuntimeLoader($runtimeLoader);

        $extension = new TwigExtension();
        $this->twig->addExtension($extension);

        if ($this->attributeName !== null) {
            $request = $request->withAttribute($this->attributeName, $this->twig);
        }

        return $handler->handle($request);
    }
}
