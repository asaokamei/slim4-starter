<?php

namespace App\Application\Container;


use DI\ContainerBuilder;
use Exception;
use Psr\Container\ContainerInterface;

class BootContainer
{
    /**
     * @var bool
     */
    private $useCache = false;

    /**
     * @var array
     */
    private $settings = [];

    /**
     * @var Provider
     */
    private $provider;

    public static function forge(array $settings): self
    {
        $self = new self();
        $self->settings = $settings;
        $self->provider = new Provider();

        return $self;
    }

    /**
     * @return ContainerInterface
     * @throws Exception
     */
    public function build(): ContainerInterface
    {
        $containerBuilder = new ContainerBuilder();

        if ($this->useCache) {
            $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
        }

        $containerBuilder->addDefinitions([
            'settings' => $this->settings,
        ]);

        // Set up dependencies
        $this->populate($containerBuilder);

        // Build PHP-DI Container instance
        return $container = $containerBuilder->build();
    }

    private function populate(ContainerBuilder $containerBuilder)
    {
        $dependencies = $this->provider->getDefinitions();
        $containerBuilder->addDefinitions($dependencies);
    }

    /**
     * @param bool $useCache
     * @return BootContainer
     */
    public function setUseCache(bool $useCache): BootContainer
    {
        $this->useCache = $useCache;
        return $this;
    }
}