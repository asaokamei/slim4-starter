<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Aura\Session\SessionFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SessionMiddleware implements Middleware
{
    const SESSION_NAME = 'session';

    /**
     * @var SessionFactory
     */
    private $sessionFactory;

    public function __construct(SessionFactory $sessionFactory)
    {
        $this->sessionFactory = $sessionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $session = $this->sessionFactory->newInstance($_COOKIE);
        $segment = $session->getSegment('app');

        $request = $request->withAttribute(self::SESSION_NAME, $segment);

        return $handler->handle($request);
    }
}
