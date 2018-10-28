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
        $paymentData = [
            "card"  => [
                "number"            => "4111111111111111",
                "expirationDate"    => "10/2022",
                "cvv"               => "737"
            ],
            "amount"            => "10.00",
            "paymentMethodNonce" => "nonceFromTheClient",
            "options" => [
              "submitForSettlement" => True
            ]
        ];

        // Test successful CC authorisation
        $braintreeService = Mockery::mock('Braintree\Gateway');
        $braintreeService->shouldReceive('transaction->sale')
                ->once()
                ->andReturn((object)[
                    'success'      =>  true
                ]);
        $this->client->setBraintreePaymentService($braintreeService);

        $result = $this->client->authorise($paymentData);


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

        $result = $this->client->authorise($paymentData);

        $this->assertInstanceOf(\Oadtz\Checkout\PaymentResult::class, $result);
        $this->assertFalse($result->getSuccess(), 'Success flag should be false.');

        // Test unknow error
        $braintreeService = Mockery::mock('Braintree\Gateway');
        $braintreeService->shouldReceive('transaction->sale')
                ->once()
                ->andThrow(\Exception::class);
        $this->client->setBraintreePaymentService($braintreeService);

        $this->expectException(\Oadtz\Checkout\Exceptions\PaymentFailedException::class);
        $result = $this->client->authorise($paymentData);
    }
}
