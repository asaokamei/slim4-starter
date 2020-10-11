<?php
namespace App\Application\Container;

class BootSetting
{
    /**
     * @var false|mixed
     */
    private $useCache;

    /**
     * @var string
     */
    private $root;

    /**
     * @var false|mixed
     */
    private $showError;

    public static function forge($useCache = false, $showError = false): self
    {
        $self = new self();
        $self->useCache = $useCache;
        $self->showError = $showError;
        $self->root = __DIR__ . '/../';

        return $self;
    }

    private function isCacheNewer($cache, $origin): bool
    {
        return filemtime($cache) >= filemtime($origin);
    }

    private function getSettingsFromCache($cacheFile, $iniFile)
    {
        if (file_exists($cacheFile) && $this->isCacheNewer($cacheFile, $iniFile)) {
            return json_decode(file_get_contents($cacheFile));
        }
        $settings = parse_ini_file($this->root . '/.env');
        file_put_contents($cacheFile, json_encode($settings));

        return $settings;
    }

    private function getSettings($cacheDir): array
    {
        $cacheFile = $cacheDir . '/env.cached.json';
        $iniFile = $this->root . '/.env';
        if ($this->useCache) {
            return $this->getSettingsFromCache($cacheFile, $iniFile);
        }
        return parse_ini_file($this->root . '/.env');
    }

    public function setup(): array
    {
        $cacheDir = $this->root . '/var/cache';
        if (!file_exists($cacheDir)) {
            mkdir($cacheDir, 0644);
        }
        $setting = $this->getSettings($cacheDir);

        $defaults = [
            'projectRoot' => $this->root,
            'cacheDirectory' => $cacheDir,
            'production' => in_array($setting['APP_ENV'], ['prod', 'production']),
            'displayErrorDetails' => false, // Should be set to false in production

        ];

        return array_merge($setting, $defaults);
    }
}