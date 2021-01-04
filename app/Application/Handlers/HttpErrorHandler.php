<?php
declare(strict_types=1);

namespace App\Application\Handlers;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpException;
use Slim\Handlers\ErrorHandler;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Views\Twig;
use Throwable;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class HttpErrorHandler extends ErrorHandler
{
    /**
     * @var Twig
     */
    private $twig;

    public function __construct(
        CallableResolverInterface $callableResolver,
        ResponseFactoryInterface $responseFactory,
        Twig $twig,
        LoggerInterface $logger
    ) {
        parent::__construct($callableResolver, $responseFactory);
        $this->twig = $twig;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    protected function respond(): Response
    {
        $exception = $this->exception;
        $statusCode = $this->exception->getCode();

        $response = $this->responseFactory->createResponse($statusCode);
        $title = $exception instanceof HttpException
            ? $exception->getTitle()
            : get_class($exception);

        $this->logger->error($title, ['file' => $exception->getFile(), 'line' => $exception->getLine()]);

        if ($this->displayErrorDetails) {
            $whoops = new Run;
            $whoops->pushHandler(new PrettyPageHandler);
            $response->getBody()->write($whoops->handleException($exception));
            return $response;
        }
        try {
            return $this->twig->render($response, 'error.twig', [
                'title' => $title,
            ]);
        } catch (Throwable $e) {
            $response->getBody()->write('<h1>error</h1>');
            return $response
                ->withStatus(500);
        }
    }
}
