<?php
/**
 * Slim Framework (http://slimframework.com)
 *
 * @license   https://github.com/slimphp/Twig-View/blob/master/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace App\Application\Twig;

use App\Application\Middleware\SessionMiddleware;
use Aura\Session\Segment;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\TwigRuntimeExtension;

class TwigFunctions extends TwigRuntimeExtension
{
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
        parent::__construct($routeParser, $request->getUri(), $basePath);
        $this->request = $request;
    }

    public function getCsrfTokens()
    {
        $name = $this->request->getAttribute('_csrf_name');
        $value = $this->request->getAttribute('_csrf_value');
        return <<< END_TAGS
<input type="hidden" name="_csrf_name" value="{$name}">
<input type="hidden" name="_csrf_value" value="{$value}">
END_TAGS;
    }

    /**
     * @return string[]
     */
    public function getFlashMessages()
    {
        /** @var Segment $session */
        $session = $this->request->getAttribute(SessionMiddleware::SESSION_NAME);
        return (array) ($session->getFlash('messages') ?? []);
    }

    /**
     * @return string[]
     */
    public function getFlashNotices()
    {
        /** @var Segment $session */
        $session = $this->request->getAttribute(SessionMiddleware::SESSION_NAME);
        return (array) ($session->getFlash('notices') ?? []);
    }
}
