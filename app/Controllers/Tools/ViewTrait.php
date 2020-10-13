<?php


namespace App\Controllers\Tools;


use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

trait ViewTrait
{
    /**
     * @var Twig
     */
    private $view;

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
        return $this->view->render($this->response, $template, $data);
    }
}