<?php
/**
 * Slim Framework (http://slimframework.com)
 *
 * @license   https://github.com/slimphp/Twig-View/blob/master/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace App\Application\Twig;

use App\Application\Session\SessionInterface;
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
     * @var SessionInterface
     */
    private $session;

    /**
     * TwigRuntimeLoader constructor.
     *
     * @param RouteParserInterface $routeParser
     * @param ServerRequestInterface $request
     * @param SessionInterface $session
     * @param string $basePath
     */
    public function __construct(
        RouteParserInterface $routeParser,
        ServerRequestInterface $request,
        SessionInterface $session,
        string $basePath = ''
    ) {
        $this->routeParser = $routeParser;
        $this->request = $request;
        $this->basePath = $basePath;
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function load(string $class)
    {
        if (TwigFunctions::class === $class) {
            return new TwigFunctions($this->routeParser, $this->request, $this->session, $this->basePath);
        }
        if (TwigFilters::class === $class) {
            return new TwigFilters();
        }

        return null;
    }
}
