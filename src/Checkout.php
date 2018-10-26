<?php

namespace Oadtz\Checkout;

class Checkout
{

    /**
     * @var  \Oadtz\Checkout\Config
     */
    private $config;

    /**
     * Sample constructor.
     *
     * @param \Oadtz\Checkout\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param $name
     *
     * @return  string
     */
    public function sayHello($name)
    {
        $greeting = $this->config->get('greeting');

        return $greeting . ' ' . $name;
    }

}
