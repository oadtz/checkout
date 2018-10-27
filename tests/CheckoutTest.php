<?php
namespace Oadtz\Checkout\Tests;

use Mockery;
use Oadtz\Checkout\Checkout;
use PHPUnit\Framework\TestCase;

class CheckoutTest extends TestCase
{
    protected $checkout;

    public function setUp () 
    {
        parent::setUp ();

        $defaultConfig = Mockery::mock('Oadtz\Checkout\Interfaces\ConfigInterface');
        $defaultConfig->shouldReceive('get')
                ->once()
                ->andReturn([]);

        $config = [];
        $this->checkout = new Checkout ($defaultConfig, $config);
    }

    public function testProcessPayment ()
    {
        $ccPayment = Mockery::mock('Oadtz\Checkout\Interfaces\PaymentInterface');
        $paymentData = [];
        $this->checkout->processPayment($ccPayment, $paymentData);

        $this->assertTrue(true);
    }

}
