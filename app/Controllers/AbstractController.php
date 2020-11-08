<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Application\Middleware\SessionMiddleware;
use Aura\Session\Segment;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use ReflectionMethod;
use Slim\App;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Views\Twig;

abstract class AbstractController
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $args;

    /**
     * @var App
     */
    private $app;

    /**
     * @var Segment
     */

    private $session;

    /**
     * @param App|null $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws HttpMethodNotAllowedException
     * @throws ReflectionException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args + $request->getQueryParams();
        $this->session = $request->getAttribute(SessionMiddleware::SESSION_NAME);
        $this->populateArgs();

        if (method_exists($this, 'action')) {
            return $this->_invokeMethod('action');
        }
        $method = 'on' . $this->determineMethod();
        if (method_exists($this, $method)) {
            return $this->_invokeMethod($method);
        }
        throw new HttpMethodNotAllowedException($request);
    }

    /**
     * Override this method to change which method to invoke.
     * Default is to use $_POST['_method'], or http method.
     *
     * @return string
     */
    protected function determineMethod(): string
    {
        return $this->request->getParsedBody()['_method'] ?? $this->request->getMethod();
    }

    /**
     * @param string $method
     * @return mixed|ResponseInterface
     * @throws ReflectionException
     */
    private function _invokeMethod(string $method)
    {
        $method = new ReflectionMethod($this, $method);
        $parameters = $method->getParameters();
        $arguments = [];
        foreach ($parameters as $arg) {
            $position = $arg->getPosition();
            $varName = $arg->getName();
            $optionValue = $arg->isOptional() ? $arg->getDefaultValue() : null;
            $value = isset($this->args[$varName]) ? $this->args[$varName] : $optionValue;
            $arguments[$position] = $value;
        }
        $method->setAccessible(true);
        return $method->invokeArgs($this, $arguments);
    }

    /**
     * alternate input arguments.
     * add 'modKeyName' method to change its value.
     * ex: user_id: '100' -> 'User100'
     *
     * return a value to override the original value.
     * or, return an associated array to add new key/value pair.
     * ex: ['user' => $user]
     */
    private function populateArgs()
    {
        foreach ($this->args as $key => $val) {
            $modifier = 'arg' . $this->snakeToCarmel($key);
            if (!method_exists($this, $modifier)) {
                continue;
            }
            $return = $this->$modifier($val);
            if (is_array($return)) {
                foreach ($return as $k => $v) {
                    $this->args[$k] = $v;
                }
            } else {
                $this->args[$key] = $return;
            }
        }
    }

    private function snakeToCarmel(string $key): string
    {
        return str_replace('_', '', ucwords($key, '_'));
    }

    /**
     * @param string $template
     * @param array $data
     * @return ResponseInterface
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function view(string $template, array $data = []): ResponseInterface
    {
        $this->session->clearFlash(); // rendering a view means ...
        $view = $this->app->getContainer()->get(Twig::class);
        /** @noinspection PhpUnhandledExceptionInspection */
        return $view->render($this->response, $template, $data);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws HttpBadRequestException
     * @noinspection PhpUnused
     */
    public function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    public function session(): Segment
    {
        return $this->session;
    }

    public function flashMessage($message)
    {
        $messages = (array) $this->session->getFlash('messages', []);
        $messages[] = $message;
        $this->session->setFlashNow('messages', $messages);
    }

    public function flashNotice($message)
    {
        $messages = (array) $this->session->getFlash('notices', []);
        $messages[] = $message;
        $this->session->setFlashNow('notices', $messages);
    }

    public function redirectToRoute(string $string, $options = [], $query = []): ResponseInterface
    {
        $routeParser = $this->app->getRouteCollector()->getRouteParser();
        $url = $routeParser->urlFor($string, $options, $query);

        return $this->response
            ->withHeader('Location', $url)
            ->withStatus(302);
    }
}
