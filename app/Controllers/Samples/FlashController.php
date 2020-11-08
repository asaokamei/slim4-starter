<?php


namespace App\Controllers\Samples;


use App\Controllers\AbstractController;

class FlashController extends AbstractController
{
    protected function determineMethod(): string
    {
        if (isset($this->args['method'])) {
            return $this->args['method'];
        }
        return 'get';
    }

    public function onGet()
    {
        $this->flashNotice('This notice is set in onGet method.');
        $this->flashMessage('This message is set in onGet method.');
        return $this->view('samples/flash.twig', []);
    }

    public function onPage()
    {
        $this->flashNotice('This notice is set in onPage method.');
        $this->flashMessage('This message is set in onPage method.');
        return $this->view('samples/flash.twig', [
            'method' => 'page',
        ]);
    }

    public function onBack()
    {
        $this->flashNotice('This notice is set in onBack method.');
        $this->flashMessage('This message is set in onBack method.');
        return $this->redirectToRoute('flashes');
    }
}