<?php
declare(strict_types=1);

namespace App\Application\Container;

use Dotenv\Dotenv;

class BootEnv
{
    const APP_ENV = 'APP_ENV';

    /**
     * @var false
     */
    private $useCache = false;

    /**
     * @var string
     */
    private $envDir;

    /**
     * @var string
     */
    private $cacheFile;

    /**
     * @var string
     */
    private $environment = 'local';

    /**
     * @param string $rootDir
     * @param string $cacheDir
     * @return static
     */
    public static function forge(string $rootDir, string $cacheDir): self
    {
        $self = new self();
        $self->envDir = $rootDir;
        $self->cacheFile = $cacheDir . '/env.cached.json';

        return $self;
    }

    private function isCacheNewer($cache, $origin): bool
    {
        return filemtime($cache) >= filemtime($origin);
    }

    private function getSettingsFromCache(): array
    {
        if (file_exists($this->cacheFile) && $this->isCacheNewer($this->cacheFile, $this->envDir)) {
            return json_decode(file_get_contents($this->cacheFile));
        }
        $settings = $this->parseEnvFile();
        if (file_exists($this->cacheFile)) {
            unlink($this->cacheFile);
        }
        if (is_array($settings)) {
            file_put_contents($this->cacheFile, json_encode($settings));
        }

        return (array) $settings;
    }

    private function getSettings(): array
    {
        if ($this->useCache) {
            return $this->getSettingsFromCache();
        }
        return $this->parseEnvFile();
    }

    public function load(): array
    {
        if (!file_exists($this->cacheFile)) {
            mkdir($this->cacheFile, 0644);
        }
        return $this->getSettings();
    }

    /**
     * @param false $useCache
     * @return static
     */
    public function setUseCache(bool $useCache)
    {
        $this->useCache = $useCache;
        return $this;
    }

    public function isProduction()
    {
        return in_array($this->environment, ['prod', 'production']);
    }

    /**
     * @return array
     */
    private function parseEnvFile(): array
    {
        $env = Dotenv::createImmutable($this->envDir);
        $settings = $env->load();
        $env->required([self::APP_ENV]);

        $this->environment = strtolower($settings[self::APP_ENV] ?? 'production');

        return $settings === false ? [] : $settings;
    }
}