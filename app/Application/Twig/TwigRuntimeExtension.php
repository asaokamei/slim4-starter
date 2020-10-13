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

class TwigRuntimeExtension
{
    /**
     * @var RouteParserInterface
     */
    protected $routeParser;

    /**
     * @var string
     */
    protected $basePath = '';

    /**
     * @var UriInterface
     */
    protected $uri;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @param RouteParserInterface $routeParser Route parser
     * @param ServerRequestInterface $request Uri
     * @param string $basePath Base path
     */
    public function __construct(RouteParserInterface $routeParser, ServerRequestInterface $request, string $basePath = '')
    {
        $this->routeParser = $routeParser;
        $this->request = $request;
        $this->uri = $request->getUri();
        $this->basePath = $basePath;
    }

    /**
     * Get the url for a named route
     *
     * @param string $routeName   Route name
     * @param array  $data        Route placeholders
     * @param array  $queryParams Query parameters
     *
     * @return string
     */
    public function urlFor(string $routeName, array $data = [], array $queryParams = []): string
    {
        return $this->routeParser->urlFor($routeName, $data, $queryParams);
    }

    /**
     * Get the full url for a named route
     *
     * @param string $routeName   Route name
     * @param array  $data        Route placeholders
     * @param array  $queryParams Query parameters
     *
     * @return string
     */
    public function fullUrlFor(string $routeName, array $data = [], array $queryParams = []): string
    {
        return $this->routeParser->fullUrlFor($this->uri, $routeName, $data, $queryParams);
    }

    /**
     * @param string $routeName Route name
     * @param array  $data      Route placeholders
     *
     * @return bool
     */
    public function isCurrentUrl(string $routeName, array $data = []): bool
    {
        $currentUrl = $this->basePath.$this->uri->getPath();
        $result = $this->routeParser->urlFor($routeName, $data);

        return $result === $currentUrl;
    }

    /**
     * Get current path on given Uri
     *
     * @param bool $withQueryString
     *
     * @return string
     */
    public function getCurrentUrl(bool $withQueryString = false): string
    {
        $currentUrl = $this->basePath.$this->uri->getPath();
        $query = $this->uri->getQuery();

        if ($withQueryString && !empty($query)) {
            $currentUrl .= '?'.$query;
        }

        return $currentUrl;
    }

    /**
     * Get the uri
     *
     * @return UriInterface
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * Set the uri
     *
     * @param UriInterface $uri
     *
     * @return self
     */
    public function setUri(UriInterface $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get the base path
     *
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * Set the base path
     *
     * @param string $basePath
     *
     * @return self
     */
    public function setBasePath(string $basePath): self
    {
        $this->basePath = $basePath;

        return $this;
    }

    public function getCsrfTokens()
    {
        $name = $this->request->getAttribute('_csrf_name');
        $value = $this->request->getAttribute('_csrf_value');
        $tags = <<< END_TAGS
<input type="hidden" name="_csrf_name" value="{$name}">
<input type="hidden" name="_csrf_value" value="{$value}">
END_TAGS;

        return $tags;
    }
}
