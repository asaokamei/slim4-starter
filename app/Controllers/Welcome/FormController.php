<?php
namespace App\Controllers\Welcome;


use App\Controllers\AbstractController;
use Psr\Http\Message\ResponseInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class WelcomeController extends AbstractController
{
    /**
     * @param string $name
     * @return ResponseInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function onGet(string $name): ResponseInterface
    {
        return $this->view('welcome.twig', [
            'name' => $name,
        ]);
    }

}