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
        $ccPayment->shouldReceive([
            'setCardHolderName' => null,
            'setCardNumber' => null,
            'setCardCVV' => null,
            'setCardExpiryDate' => null,
            'setAmount' =>  null,
            'setCurrency' => null,
            'setSupplementData' => null,
            'pay' => new \Oadtz\Checkout\PaymentResult()
        ]);
        $this->checkout = new Checkout ($ccPayment);
    }

    public function testSetCardHolderName ()
    {
        $result = $this->checkout->setCardHolderName('John Wick');

        $this->assertEquals (null, $result);
    }

    public function testSetCardNumber ()
    {
        $result = $this->checkout->setCardNumber('4111111111111111');

        $this->assertEquals (null, $result);
    }

    public function testSetCardCVV ()
    {
        $result = $this->checkout->setCardCVV('373');

        $this->assertEquals (null, $result);
    }

    public function testSetCardExpiryDate ()
    {
        $result = $this->checkout->setCardExpiryDate(new \DateTime());

        $this->assertEquals (null, $result);
    }

    public function testSetAmount ()
    {
        $result = $this->checkout->setAmount(100.00);

        $this->assertEquals (null, $result);
    }

    public function testSetCurrency ()
    {
        $result = $this->checkout->setCurrency('THB');

        $this->assertEquals (null, $result);
    }

    public function testSetSupplementData ()
    {
        $result = $this->checkout->setSupplementData([]);

        $this->assertEquals (null, $result);
    }

    public function testProcessPayment ()
    {
        $paymentInfo = Mockery::mock('Oadtz\Checkout\PaymentInfo');
        $result = $this->checkout->processPayment($paymentInfo);

        $this->assertInstanceOf(\Oadtz\Checkout\PaymentResult::class, $result);
    }

}
