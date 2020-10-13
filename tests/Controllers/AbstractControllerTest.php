<?php

namespace Tests\Controllers;

use App\AppBuilder;
use App\Application\Container\Provider;
use App\Controllers\AbstractController;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Views\Twig;
use Slim\Views\TwigRuntimeLoader;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class AbstractControllerTest extends TestCase
{
    /**
     * @var TestController
     */
    private $test;

    protected function setUp(): void
    {
        $class = new \ReflectionClass(TestController::class);
        $this->test = $class->newInstanceWithoutConstructor();
    }

    public function testOnGet()
    {
        $request = new ServerRequest('GET', '');
        $response = new Response(200, [], '');
        $returned = $this->test->__invoke($request, $response, []);

        $this->assertTrue($returned instanceof ResponseInterface);
        $returned->getBody()->rewind();
        $this->assertEquals('tested onGet', $returned->getBody()->getContents());
    }

    public function testOnPost()
    {
        $request = new ServerRequest('POST', '');
        $response = new Response(200, [], '');
        $returned = $this->test->__invoke($request, $response, []);

        $this->assertTrue($returned instanceof ResponseInterface);
        $returned->getBody()->rewind();
        $this->assertEquals('tested onPost', $returned->getBody()->getContents());
    }

    public function testMethodOverride()
    {
        $request = new ServerRequest('GET', '');
        $request = $request->withAttribute('_method', 'MethodOverride');
        $response = new Response(200, [], '');
        $returned = $this->test->__invoke($request, $response, []);

        $this->assertTrue($returned instanceof ResponseInterface);
        $returned->getBody()->rewind();
        $this->assertEquals('tested onMethodTest', $returned->getBody()->getContents());
    }

    public function testArgs()
    {
        $request = new ServerRequest('GET', '');
        $request = $request->withAttribute('_method', 'Args');
        $response = new Response(200, [], '');
        $returned = $this->test->__invoke($request, $response, ['test' => 'testArgs']);

        $this->assertTrue($returned instanceof ResponseInterface);
        $returned->getBody()->rewind();
        $this->assertEquals('tested onArgs(testArgs)', $returned->getBody()->getContents());
    }

    public function testArgMod()
    {
        $request = new ServerRequest('GET', '');
        $request = $request->withAttribute('_method', 'ArgMod');
        $response = new Response(200, [], '');
        $returned = $this->test->__invoke($request, $response, ['argMod' => 'modArgs']);

        $this->assertTrue($returned instanceof ResponseInterface);
        $returned->getBody()->rewind();
        $this->assertEquals('tested onArgMod(mod:modArgs)', $returned->getBody()->getContents());
    }

    public function testNewValue()
    {
        $request = new ServerRequest('GET', '');
        $request = $request->withAttribute('_method', 'NewValue');
        $response = new Response(200, [], '');
        $returned = $this->test->__invoke($request, $response, ['oldValue' => 'testNewValue']);

        $this->assertTrue($returned instanceof ResponseInterface);
        $returned->getBody()->rewind();
        $this->assertEquals('tested onNewValue(mod:testNewValue)', $returned->getBody()->getContents());
    }
}
