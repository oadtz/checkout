<?php

namespace Oadtz\Checkout\Tests;

use Oadtz\Checkout\Config;
use Oadtz\Checkout\Checkout;

class SampleTest extends TestCase
{

    public function testSayHello()
    {
        $config = new Config();
        $sample = new Checkout($config);

        $name = 'Thanapat Pirmphol';

        $result = $sample->sayHello($name);

        $expected = $config->get('greeting') . ' ' . $name;

        $this->assertEquals($result, $expected);

    }

}
