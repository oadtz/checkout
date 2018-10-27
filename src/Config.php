<?php
namespace Oadtz\Checkout;

use Oadtz\Checkout\Interfaces\ConfigInterface;
use Oadtz\Checkout\Exceptions\ConfigFileNotFoundException;

class Config implements ConfigInterface
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
        // the config file of the package directory
        $config_path = __DIR__ . '/Config';

        // check if this laravel specific function `config_path()` exist (means this package is used inside
        // a laravel framework). If so then load then try to load the laravel config file if it exist.
        if (function_exists('config_path')) {
            $config_path = config_path();
        }

        $config_file = $config_path . DIRECTORY_SEPARATOR . self::CONFIG_FILE_NAME . '.php';

        if (!file_exists($config_file)) {
            throw new ConfigFileNotFoundException();
        }

        $this->config = require $config_file;
    }

    /**
     * @param $key
     *
     * @return  mixed
     */
    public function get($key = null)
    {
        if (!is_null ($key))
            return $this->config[$key];
        return $this->config;
    }
}
