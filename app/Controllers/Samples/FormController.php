<?php
namespace App\Controllers\Samples;


use App\Controllers\AbstractController;
use Psr\Http\Message\ResponseInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class FormController extends AbstractController
{
    /**
     * @return ResponseInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function onGet(): ResponseInterface
    {
        return $this->view('samples/form.twig', [
            'form' => 'not yet ready',
            'csrf' => [$this->csrfTokenName(), $this->csrfTokenValue()],
        ]);
    }

}