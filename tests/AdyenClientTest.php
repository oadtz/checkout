<?php
namespace Oadtz\Checkout\Tests;

use Mockery;
use Oadtz\Checkout\AdyenClient;
use PHPUnit\Framework\TestCase;

class AdyenClientTest extends TestCase
{
    protected $client;

    public function setUp ()
    {
        parent::setUp();
    }

    public function testPay()
    {
        $paymentData = [
            "card"  => [
                "number"        => "4111111111111111",
                "expiryMonth"   => "10",
                "expiryYear"    => "2022",
                "cvc"           => "737",
                "holderName"    => "John Smith"
            ],
            "amount"            => [
              "value"           => 1500,
              "currency"        => "EUR"
            ],
            "reference"         => "MY_REFERENCE",
            "merchantAccount"   => "MY_MERCHANT_ACCOUNT"
        ];

        // Test successful CC authorisation
        $adyenService = Mockery::mock('Adyen\Service\Payment');
        $adyenService->shouldReceive('authorise')
                ->once()
                ->andReturn([
                    'pspReference'      =>  '8515405668712188',
                    'resultCode'        =>  'Authorised',
                    'authCode'          =>  '56065'
                ]);
        $client = new AdyenClient ($adyenService);

        $result = $client->authorise($paymentData);


        $this->assertInstanceOf(\Oadtz\Checkout\PaymentResult::class, $result);
        $this->assertTrue($result->getSuccess(), 'Success flag should be true.');
        $this->assertEquals('adyen', $result->getPaymentGateway());



        // Test failure CC authorisation
        $adyenService = Mockery::mock('Adyen\Service\Payment');
        $adyenService->shouldReceive('authorise')
                ->once()
                ->andReturn([
                    'status'            =>  422,
                    'errorCode'         =>  101,
                    'message'           =>  'Invalid card number',
                    'errorType'         =>  'validation',
                    'pspReference'      =>  '8815405669507360'
                ]);
        $client = new AdyenClient ($adyenService);

        $result = $client->authorise($paymentData);

        $this->assertInstanceOf(\Oadtz\Checkout\PaymentResult::class, $result);
        $this->assertFalse($result->getSuccess(), 'Success flag should be false.');

        // Test unknow error
        $adyenService = Mockery::mock('Adyen\Service\Payment');
        $adyenService->shouldReceive('authorise')
                ->once()
                ->andThrow(\Exception::class);
        $client = new AdyenClient ($adyenService);

        $this->expectException(\Oadtz\Checkout\Exceptions\PaymentFailedException::class);
        $result = $client->authorise($paymentData);
    }
}
