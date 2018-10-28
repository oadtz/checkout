<?php
namespace Oadtz\Checkout\Tests;

use Mockery;
use Oadtz\Checkout\BraintreeClient;
use PHPUnit\Framework\TestCase;

class BraintreeClientTest extends TestCase
{
    protected $client;

    public function setUp ()
    {
        parent::setUp();

        $defaultConfig = Mockery::mock('\Oadtz\Checkout\Interfaces\ConfigInterface');
        $defaultConfig->shouldReceive('get')
                      ->andReturn([
                        'environment'       =>  'sandbox'
                      ]);
        $this->client = new BraintreeClient($defaultConfig, []);
    }

    public function testPay()
    {
        $paymentInfo = Mockery::mock('\Oadtz\Checkout\PaymentInfo');
        $paymentInfo->shouldReceive([
            'getCardNumber'         => '4111111111111111', 
            'getCardExpiryDate'     =>  new \DateTime('2020-01-01'), 
            'getCardCVV'            =>  '737', 
            'getCardHolderName'     =>  'John Smith', 
            'getAmount'             =>  100.00, 
            'getCurrency'           =>  'EUR', 
            'getSupplementData'     => [
                "paymentMethodNonce"         => null,
                "options" => [
                  "submitForSettlement" => True
                ]
            ]
        ]);
        $paymentInfo->shouldReceive('getCardNumber');

        // Test successful CC authorisation
        $braintreeService = Mockery::mock('Braintree\Gateway');
        $braintreeService->shouldReceive('transaction->sale')
                ->once()
                ->andReturn((object)[
                    'success'      =>  true
                ]);
        $this->client->setBraintreePaymentService($braintreeService);

        $result = $this->client->authorise($paymentInfo);


        $this->assertInstanceOf(\Oadtz\Checkout\PaymentResult::class, $result);
        $this->assertTrue($result->getSuccess(), 'Success flag should be true.');
        $this->assertEquals('braintree', $result->getPaymentGateway());



        // Test failure CC authorisation
        $braintreeService = Mockery::mock('Braintree\Gateway');
        $braintreeService->shouldReceive('transaction->sale')
                ->once()
                ->andReturn((object)[
                    'success'   =>  false
                ]);
        $this->client->setBraintreePaymentService($braintreeService);

        $result = $this->client->authorise($paymentInfo);

        $this->assertInstanceOf(\Oadtz\Checkout\PaymentResult::class, $result);
        $this->assertFalse($result->getSuccess(), 'Success flag should be false.');

        // Test unknow error
        $braintreeService = Mockery::mock('Braintree\Gateway');
        $braintreeService->shouldReceive('transaction->sale')
                ->once()
                ->andThrow(\Exception::class);
        $this->client->setBraintreePaymentService($braintreeService);

        $this->expectException(\Oadtz\Checkout\Exceptions\PaymentFailedException::class);
        $result = $this->client->authorise($paymentInfo);
    }
}
