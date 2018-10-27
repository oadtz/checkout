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
        $result = $this->payment->pay ([
            'currency'                  =>  'USD',
            'creditcard_number'         =>  '371449635398431', //Amex
            'creditcard_expiry_month'   =>  '09',
            'creditcard_expiry_year'    =>  '2022',
            'creditcard_cvv'            =>  '737',
            'creditcard_holder_name'    =>  'John Wick'
        ]);
        
        $this->assertInstanceOf(\Oadtz\Checkout\PaymentResult::class, $result);
        $this->assertEquals('adyen', $result->getPaymentGateway());


        $result = $this->payment->pay ([
            'currency'                  =>  'USD',
            'creditcard_number'         =>  '6011527312385806', //Discover
            'creditcard_expiry_month'   =>  '09',
            'creditcard_expiry_year'    =>  '2022',
            'creditcard_cvv'            =>  '737',
            'creditcard_holder_name'    =>  'John Wick'
        ]);
        
        $this->assertEquals('adyen', $result->getPaymentGateway());


        $result = $this->payment->pay ([
            'currency'                  =>  'EUR',
            'creditcard_number'         =>  '6011527312385806', //Discover
            'creditcard_expiry_month'   =>  '09',
            'creditcard_expiry_year'    =>  '2022',
            'creditcard_cvv'            =>  '737',
            'creditcard_holder_name'    =>  'John Wick'
        ]);
        
        $this->assertEquals('adyen', $result->getPaymentGateway());


        $result = $this->payment->pay ([
            'currency'                  =>  'AUD',
            'creditcard_number'         =>  '6011527312385806', //Discover
            'creditcard_expiry_month'   =>  '09',
            'creditcard_expiry_year'    =>  '2022',
            'creditcard_cvv'            =>  '737',
            'creditcard_holder_name'    =>  'John Wick'
        ]);
        
        $this->assertEquals('adyen', $result->getPaymentGateway());
    }

    public function testPayWithBraintree ()
    {
        $result = $this->payment->pay ([
            'currency'                  =>  'THB',
            'creditcard_number'         =>  '6011527312385806', //Discover
            'creditcard_expiry_month'   =>  '09',
            'creditcard_expiry_year'    =>  '2022',
            'creditcard_cvv'            =>  '737',
            'creditcard_holder_name'    =>  'John Wick'
        ]);
        
        $this->assertInstanceOf(\Oadtz\Checkout\PaymentResult::class, $result);
        $this->assertEquals('braintree', $result->getPaymentGateway());
    }

    public function testPayWithAmexAndNonUSD ()
    {
        $this->expectException(\Oadtz\Checkout\Exceptions\PaymentFailedException::class);
        $result = $this->payment->pay ([
            'currency'                  =>  'THB',
            'creditcard_number'         =>  '371449635398431', //Amex
            'creditcard_expiry_month'   =>  '09',
            'creditcard_expiry_year'    =>  '2022',
            'creditcard_cvv'            =>  '737',
            'creditcard_holder_name'    =>  'John Wick'
        ]);
    }
}
