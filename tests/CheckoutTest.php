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
        $this->checkout = new Checkout ();
    }

    public function testProcessPayment ()
    {
        $ccPayment = Mockery::mock('Oadtz\Checkout\Interfaces\PaymentInterface');
        $ccPayment->shouldReceive('pay')
                  ->andReturn(new \Oadtz\Checkout\PaymentResult());
        $paymentData = [];
        $result = $this->checkout->processPayment($ccPayment, $paymentData);

        $this->assertInstanceOf(\Oadtz\Checkout\PaymentResult::class, $result);
    }

}
