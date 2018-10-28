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


        $ccPayment = Mockery::mock('Oadtz\Checkout\Interfaces\PaymentInterface');
        $ccPayment->shouldReceive('pay')
                  ->andReturn(new \Oadtz\Checkout\PaymentResult());
        $this->checkout = new Checkout ($ccPayment);
    }

    public function testProcessPayment ()
    {
        $paymentData = [];
        $result = $this->checkout->processPayment($paymentData);

        $this->assertInstanceOf(\Oadtz\Checkout\PaymentResult::class, $result);
    }

}
