<?php


namespace Tests\Controllers;


use App\Controllers\AbstractController;

class TestController extends AbstractController
{
    public function onGet()
    {
        $this->response->getBody()->write('tested onGet');
        return $this->response;
    }

    public function onPost()
    {
        $this->response->getBody()->write('tested onPost');
        return $this->response;
    }

    public function onMethodOverride()
    {
        $this->response->getBody()->write('tested onMethodTest');
        return $this->response;
    }

    public function onArgs($test)
    {
        $this->response->getBody()->write("tested onArgs({$test})");
        return $this->response;
    }

    public function onArgMod($argMod)
    {
        $this->response->getBody()->write("tested onArgMod({$argMod})");
        return $this->response;
    }

    public function onNewValue($newValue)
    {
        $this->response->getBody()->write("tested onNewValue({$newValue})");
        return $this->response;
    }

    protected function argArgMod($value)
    {
        return "mod:{$value}";
    }

    protected function argOldValue($value)
    {
        return ['newValue' => "mod:{$value}"];
    }
}