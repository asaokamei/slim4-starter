<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\Forms;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Validation;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        /**
         * logger/Monolog
         */
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $logger = new Logger('slim-app');

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $path = $settings['projectRoot'].'/var/app.log';
            $level = $settings['production'] ?? false
                    ? Logger::ERROR
                    : Logger::DEBUG;
            $handler = new StreamHandler($path, $level);
            $logger->pushHandler($handler);

            return $logger;
        },
        Twig::class => function (ContainerInterface $c) {
            return Twig::create(__DIR__ . '/templates', [
                'cache' => __DIR__ . '/../var/cache/twig',
                'auto_reload' => true,
            ]);
        },
        'view' => DI\get(Twig::class),
    ]);

};
