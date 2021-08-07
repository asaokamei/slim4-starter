<?php
declare(strict_types=1);

namespace App\Application\Container;

use Dotenv\Dotenv;
use Throwable;

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
     * @var array
     */
    private $settings;

    /**
     * @param string $rootDir
     * @param string $cacheDir
     * @return static
     */
    public static function forge(string $rootDir, string $cacheDir): self
    {
        $self = new self();
        $self->envDir = $rootDir;
        $self->cacheFile = rtrim($cacheDir, '/') . '/env.cached.json';

        return $self;
    }

    private function isCacheNewer($cache, $origin): bool
    {
        return filemtime($cache) >= filemtime($origin);
    }

    private function parseFromCacheFile(): array
    {
        if (file_exists($this->cacheFile) && $this->isCacheNewer($this->cacheFile, $this->envDir)) {
            try {
                return json_decode(file_get_contents($this->cacheFile), true);
            } catch (Throwable $e) {
                unlink($this->cacheFile);
                return $this->parseEnvFile();
            }
        }
        $settings = $this->parseEnvFile();
        if (is_array($settings)) {
            file_put_contents($this->cacheFile, json_encode($settings, JSON_UNESCAPED_UNICODE), LOCK_EX);
        }

        return (array) $settings;
    }

    public function load(): void
    {
        if ($this->useCache) {
            $this->settings = $this->parseFromCacheFile();
        }
        $this->settings = $this->parseEnvFile();
    }

    /**
     * @param false $useCache
     * @return static
     */
    public function setUseCache(bool $useCache): BootEnv
    {
        $this->useCache = $useCache;
        return $this;
    }

    public function isProduction(): bool
    {
        return in_array($this->environment, ['prod', 'production']);
    }

    /**
     * @return array
     */
    private function parseEnvFile(): array
    {
        $env = Dotenv::createImmutable($this->envDir);
        $settings = $env->safeLoad();
        $env->required([self::APP_ENV]);

        $this->environment = strtolower($settings[self::APP_ENV] ?? 'production');

        return $settings;
    }

    /**
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }
}