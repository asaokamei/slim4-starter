<?php


namespace App\Application\Container;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;

class Provider
{
    public function getDefinitions(): array
    {
        $list = [
            LoggerInterface::class => 'getMonolog',
            Twig::class => 'getTwig',

            'view' => \DI\get(Twig::class),
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