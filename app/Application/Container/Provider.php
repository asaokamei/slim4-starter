<?php
declare(strict_types=1);

namespace App\Application\Container;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\Csrf\Guard;
use Slim\Views\Twig;
use function DI\get;

class Provider
{
    public function getDefinitions(): array
    {
        $list = [
            ResponseFactoryInterface::class => get(Psr17Factory::class),
            Psr17Factory::class => 'getPsr17Factory',
            LoggerInterface::class => 'getMonolog',
            Twig::class => 'getTwig',
            Guard::class => 'getCsrfGuard',

            'view' => get(Twig::class),
            'csrf' => get(Guard::class),
        ];
        return $this->prepare($list);
    }

    private function prepare(array $list): array
    {
        foreach ($list as $key => $item) {
            if (is_string($item)) {
                $list[$key] = function(ContainerInterface $c) use($item) {
                    return $this->$item($c);
                };
            }
        }
        return $list;
    }

    private function getPsr17Factory()
    {
        return new Psr17Factory();
    }

    private function getCsrfGuard(ContainerInterface $c)
    {
        $guard = new Guard($c->get(ResponseFactoryInterface::class), '_csrf');
        $guard->setPersistentTokenMode(true);

        return $guard;
    }

    private function getMonolog(ContainerInterface $c)
    {
        $settings = $c->get('settings');

        $logger = new Logger($settings['app_name']??'slim-app');

        $processor = new UidProcessor();
        $logger->pushProcessor($processor);

        $path = $settings['projectRoot'] . '/var/app.log';
        $level = $settings['production'] ?? false
                ? Logger::ERROR
                : Logger::DEBUG;
        $handler = new StreamHandler($path, $level);
        $logger->pushHandler($handler);

        return $logger;
    }

    private function getTwig(ContainerInterface $c)
    {
        $settings = $c->get('settings');

        $tempDir = $settings['projectRoot'] . '/app/templates';
        $cacheDir = $settings['cacheDirectory'] . '/twig';
        return Twig::create($tempDir, [
            'cache' => $cacheDir,
            'auto_reload' => true,
        ]);
    }
}