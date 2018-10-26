<?php

namespace Oadtz\Checkout;

use Illuminate\Config\Repository;
use Oadtz\Checkout\Exceptions\ConfigFileNotFoundException;

class Config
{

    /**
     * Config file name
     */
    CONST CONFIG_FILE_NAME = "checkout";

    /**
     * @var  \Illuminate\Config\Repository
     */
    private $config;

    /**
     * Config constructor.
     *
     * @param \Illuminate\Config\Repository $config
     */
    public function __construct()
    {
        $configPath = $this->configurationPath();

        $config_file = $configPath . DIRECTORY_SEPARATOR . self::CONFIG_FILE_NAME . '.php';

        if (!file_exists($config_file)) {
            throw new ConfigFileNotFoundException();
        }

        $this->config = new Repository(require $config_file);
    }

    /**
     * return the correct config directory path
     *
     * @return  mixed|string
     */
    private function configurationPath()
    {
        // the config file of the package directory
        $config_path = __DIR__ . '/Config';

        // check if this laravel specific function `config_path()` exist (means this package is used inside
        // a laravel framework). If so then load then try to load the laravel config file if it exist.
        if (function_exists('config_path')) {
            $config_path = config_path();
        }

        return $config_path;
    }

    /**
     * @param $key
     *
     * @return  mixed
     */
    public function get($key)
    {
        return $this->config->get($key);
    }
}
