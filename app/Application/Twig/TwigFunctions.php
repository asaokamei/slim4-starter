<?php
/**
 * Slim Framework (http://slimframework.com)
 *
 * @license   https://github.com/slimphp/Twig-View/blob/master/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace App\Application\Twig;

use App\Application\Middleware\SessionMiddleware;
use App\Application\Session\SessionInterface;
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
     * @var SessionInterface
     */
    private $session;

    /**
     * @param RouteParserInterface $routeParser Route parser
     * @param ServerRequestInterface $request Uri
     * @param SessionInterface $session
     * @param string $basePath Base path
     */
    public function __construct(
        RouteParserInterface $routeParser,
        ServerRequestInterface $request,
        SessionInterface $session,
        string $basePath = ''
    ) {
        parent::__construct($routeParser, $request->getUri(), $basePath);
        $this->request = $request;
        $this->session = $session;
    }

    public function getCsrfTokens(): string
    {
        $name = SessionInterface::POST_TOKEN_NAME;
        $value = $this->session->getCsRfToken();
        return <<< END_TAGS
<input type="hidden" name="{$name}" value="{$value}">
END_TAGS;
    }

    /**
     * @return string[]
     */
    public function getFlashMessages(): array
    {
        return (array) ($this->session->getFlash('messages') ?? []);
    }

    /**
     * @return string[]
     */
    public function getFlashNotices(): array
    {
        return (array) ($this->session->getFlash('notices') ?? []);
    }
}
