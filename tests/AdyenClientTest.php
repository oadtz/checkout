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

        $defaultConfig = Mockery::mock('\Oadtz\Checkout\Interfaces\ConfigInterface');
        $defaultConfig->shouldReceive('get')
                      ->andReturn([]);
        $this->client = new AdyenClient($defaultConfig, ['environment' => 'test']);
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
                "reference"         => "MY_REFERENCE",
                "merchantAccount"   => "MY_MERCHANT_ACCOUNT",
                "additionalData"    =>  [
                    "card.encrypted.json" =>  null
                ]
            ]
        ]);

        // Test successful CC authorisation
        $adyenService = Mockery::mock('Adyen\Service\Payment');
        $adyenService->shouldReceive('authorise')
                ->once()
                ->andReturn([
                    'pspReference'      =>  '8515405668712188',
                    'resultCode'        =>  'Authorised',
                    'authCode'          =>  '56065'
                ]);
        $this->client->setAdyenPaymentService($adyenService);

        $result = $this->client->authorise($paymentInfo);


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
        $this->client->setAdyenPaymentService($adyenService);
        
        $result = $this->client->authorise($paymentInfo);

        $this->assertInstanceOf(\Oadtz\Checkout\PaymentResult::class, $result);
        $this->assertFalse($result->getSuccess(), 'Success flag should be false.');

        // Test unknow error
        $adyenService = Mockery::mock('Adyen\Service\Payment');
        $adyenService->shouldReceive('authorise')
                ->once()
                ->andThrow(\Exception::class);
        $this->client->setAdyenPaymentService($adyenService);

        $this->expectException(\Oadtz\Checkout\Exceptions\PaymentFailedException::class);
        $result = $this->client->authorise($paymentInfo);
    }
}
