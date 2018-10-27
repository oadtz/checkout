<?php
namespace Oadtz\Checkout\Tests;

use Oadtz\Checkout\PaymentResult;
use PHPUnit\Framework\TestCase;

class PaymentResultTest extends TestCase {
    protected $paymentResult;

    public function setUp ()
    {
        parent::setUp ();

        $this->paymentResult = new PaymentResult();
    }

    public function tearDown () 
    {
        unset ($this->paymentResult);
    }

    public function testConstructor ()
    {
        $this->paymentResult = new PaymentResult ([
            'success'       =>  true,
            'responseData'  =>  [
                'fakeData'      =>  true
            ]
        ]);
        $this->assertSame (true, $this->paymentResult->getSuccess());


        $this->paymentResult = new PaymentResult ([
            'responseData'  =>  [
                'fakeData'      =>  true
            ]
        ]);
        $this->assertSame (false, $this->paymentResult->getSuccess());


        $this->paymentResult = new PaymentResult ([]);
        $this->assertSame (null, $this->paymentResult->getResponseData());
    }

    public function testGetSetAttributes ()
    {
        $this->assertSame (false, $this->paymentResult->getSuccess(), 'If did not set success should return false.');

        $this->paymentResult->setSuccess (true);
        $this->assertSame (true, $this->paymentResult->getSuccess(), 'If set to true should return true.');

        $this->assertSame (null, $this->paymentResult->getPaymentGateway(), 'If did not set paymentGateway should return null.');
        $this->assertSame (null, $this->paymentResult->getResponseData(), 'If did not set responseData should return null.');

        $this->paymentResult->setPaymentGateway ('braintree');
        $this->assertSame ('braintree', $this->paymentResult->getPaymentGateway(), 'If set to braintree should return braintree.');

        $this->paymentResult->setResponseData ([ 'fakeData' => true ]);
        $this->assertSame ([ 'fakeData' => true ], $this->paymentResult->getResponseData(), 'If set to x should return x.');
    }

}