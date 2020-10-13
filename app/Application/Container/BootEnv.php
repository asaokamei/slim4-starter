<?php
declare(strict_types=1);

namespace App\Application\Container;

class BootEnv
{
    const APP_ENV = 'APP_ENV';

    /**
     * @var false|mixed
     */
    private $useCache = false;

    /**
     * @var string
     */
    private $envFile;

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
    public static function forge($rootDir, $cacheDir): self
    {
        $self = new self();
        $self->envFile = $rootDir . '/.env';
        $self->cacheFile = $cacheDir . '/env.cached.json';

        return $self;
    }

    private function isCacheNewer($cache, $origin): bool
    {
        return filemtime($cache) >= filemtime($origin);
    }

    private function getSettingsFromCache(): array
    {
        if (file_exists($this->cacheFile) && $this->isCacheNewer($this->cacheFile, $this->envFile)) {
            return json_decode(file_get_contents($this->cacheFile));
        }
        $settings = $this->parseEnvFile();;
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
     * @param false|mixed $useCache
     * @return static
     */
    public function setUseCache($useCache)
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
        $settings = parse_ini_file($this->envFile);

        $this->environment = strtolower($settings[self::APP_ENV] ?? 'production');

        return $settings === false ? [] : $settings;
    }
}