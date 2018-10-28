<?php
namespace Oadtz\Checkout\Tests;

use Mockery;
use Oadtz\Checkout\CreditCardPayment;
use PHPUnit\Framework\TestCase;


class CreditCardPaymentTest extends TestCase
{
    protected $payment;

    public function setUp ()
    {
        parent::setUp ();

        
        $adyen = Mockery::mock ('\Oadtz\Checkout\AdyenClient');
        $adyen->shouldReceive('authorise')
              ->andReturn(new \Oadtz\Checkout\PaymentResult([
                  'paymentGateway'      =>  'adyen'
              ]));
        $braintree = Mockery::mock ('\Oadtz\Checkout\BraintreeClient');
        $braintree->shouldReceive('authorise')
              ->andReturn(new \Oadtz\Checkout\PaymentResult([
                  'paymentGateway'      =>  'braintree'
              ]));

        $this->payment = new CreditCardPayment($adyen, $braintree);
    }

    public function testPayWithAdyen ()
    {
        $paymentInfo = Mockery::mock('Oadtz\Checkout\PaymentInfo');
        $paymentInfo->shouldReceive([
            'getCardNumber'     =>  '371449635398431', //Amex
            'getCurrency'       =>  'USD'
        ]);
        $result = $this->payment->pay ($paymentInfo);
        
        $this->assertInstanceOf(\Oadtz\Checkout\PaymentResult::class, $result);
        $this->assertEquals('adyen', $result->getPaymentGateway());


        $paymentInfo = Mockery::mock('Oadtz\Checkout\PaymentInfo');
        $paymentInfo->shouldReceive([
            'getCardNumber'     =>  '6011527312385806', //Discover
            'getCurrency'       =>  'USD'
        ]);
        $result = $this->payment->pay ($paymentInfo);
        
        $this->assertEquals('adyen', $result->getPaymentGateway());


        $paymentInfo = Mockery::mock('Oadtz\Checkout\PaymentInfo');
        $paymentInfo->shouldReceive([
            'getCardNumber'     =>  '6011527312385806', //Discover
            'getCurrency'       =>  'EUR'
        ]);
        $result = $this->payment->pay ($paymentInfo);
        
        $this->assertEquals('adyen', $result->getPaymentGateway());


        $paymentInfo = Mockery::mock('Oadtz\Checkout\PaymentInfo');
        $paymentInfo->shouldReceive([
            'getCardNumber'     =>  '6011527312385806', //Discover
            'getCurrency'       =>  'AUD'
        ]);
        $result = $this->payment->pay ($paymentInfo);
        
        $this->assertEquals('adyen', $result->getPaymentGateway());
    }

    public function testPayWithBraintree ()
    {
        $paymentInfo = Mockery::mock('Oadtz\Checkout\PaymentInfo');
        $paymentInfo->shouldReceive([
            'getCardNumber'     =>  '6011527312385806', //Discover
            'getCurrency'       =>  'THB'
        ]);
        $result = $this->payment->pay ($paymentInfo);
        
        $this->assertInstanceOf(\Oadtz\Checkout\PaymentResult::class, $result);
        $this->assertEquals('braintree', $result->getPaymentGateway());
    }

    public function testPayWithAmexAndNonUSD ()
    {
        $this->expectException(\Oadtz\Checkout\Exceptions\PaymentFailedException::class);
        $paymentInfo = Mockery::mock('Oadtz\Checkout\PaymentInfo');
        $paymentInfo->shouldReceive([
            'getCardNumber'     =>  '371449635398431', //Amex
            'getCurrency'       =>  'THB'
        ]);
        $result = $this->payment->pay ($paymentInfo);
    }
}
