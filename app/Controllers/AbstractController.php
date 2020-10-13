<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use ReflectionMethod;
use Slim\App;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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
     * @throws HttpMethodNotAllowedException|ReflectionException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args + $request->getQueryParams();
        $this->populateArgs();

        $method = 'on' . ($request->getAttribute('_method') ?? $request->getMethod());
        if (method_exists($this, 'action')) {
            return $this->_invokeMethod('action');
        }
        if (method_exists($this, $method)) {
            return $this->_invokeMethod($method);
        }
        throw new HttpMethodNotAllowedException($request);
    }

    /**
     * @param string $method
     * @return mixed|ResponseInterface
     * @throws ReflectionException
     */
    private function _invokeMethod($method)
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
     * ex: user_id -> argUserId
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
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function view(string $template, array $data = []): ResponseInterface
    {
        $view = $this->app->getContainer()->get(Twig::class);
        return $view->render($this->response, $template, $data);
    }

    protected function csrfTokenName(): string
    {
        return $this->request->getAttribute('_csrf_name');
    }

    protected function csrfTokenValue(): string
    {
        return $this->request->getAttribute('_csrf_value');
    }

    /**
     * @param string $name
     * @return mixed
     * @throws HttpBadRequestException
     * @noinspection PhpUnused
     */
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }
}
