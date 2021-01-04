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
use Slim\App;
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
     * @var App
     */
    private $app;

    /**
     * @param App $app
     * @param ServerRequestInterface $request Uri
     * @param SessionInterface $session
     */
    public function __construct(
        App $app,
        ServerRequestInterface $request,
        SessionInterface $session
    ) {
        parent::__construct($app->getRouteCollector()->getRouteParser(), $request->getUri(), $app->getBasePath());
        $this->request = $request;
        $this->session = $session;
        $this->app = $app;
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
