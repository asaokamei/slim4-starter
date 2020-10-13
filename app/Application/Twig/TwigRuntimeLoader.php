<?php
/**
 * Slim Framework (http://slimframework.com)
 *
 * @license   https://github.com/slimphp/Twig-View/blob/master/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace App\Application\Twig;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Slim\Interfaces\RouteParserInterface;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

class TwigRuntimeLoader implements RuntimeLoaderInterface
{
    /**
     * @var RouteParserInterface
     */
    protected $routeParser;

    /**
     * @var UriInterface
     */
    protected $request;

    /**
     * @var string
     */
    protected $basePath = '';

    /**
     * TwigRuntimeLoader constructor.
     *
     * @param RouteParserInterface $routeParser
     * @param ServerRequestInterface $request
     * @param string $basePath
     */
    public function __construct(RouteParserInterface $routeParser, ServerRequestInterface $request, string $basePath = '')
    {
        $this->routeParser = $routeParser;
        $this->request = $request;
        $this->basePath = $basePath;
    }

    /**
     * {@inheritdoc}
     */
    public function load(string $class)
    {
        if (TwigRuntimeExtension::class === $class) {
            return new $class($this->routeParser, $this->request, $this->basePath);
        }

        return null;
    }
}
