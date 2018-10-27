<?php
namespace Oadtz\Checkout\Tests;

use Oadtz\Checkout\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    protected $config;
    protected $config_file;

    public function setUp () 
    {
        parent::setUp();
        
        $this->config = new Config();
        $this->config_file = 'src/Config/checkout.php';
    }

    public function tearDown ()
    {
        unset ($this->config);
        unset ($this->config_file);
    }

    public function testGetSingleConfig()
    {
        $input = require $this->config_file;
       
        $this->assertSame ($input['braintree'], $this->config->get('braintree'), 'Should return single config value.');
    }

    public function testGetAllConfig()
    {
        $input = require $this->config_file;

        $this->assertSame ($input, $this->config->get(), 'Should return all config values.');
    }

}
