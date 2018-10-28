<?php
namespace Oadtz\Checkout;

use Oadtz\Checkout\Interfaces\{ConfigInterface, PaymentClientInterface};
use Oadtz\Checkout\{PaymenrInfo, PaymentResult};

class AdyenClient implements PaymentClientInterface {
    protected $config, $service;

    /**
     * @param ConfigInterface $defaultConfig
     * @param array $config = []
     */
    public function __construct (ConfigInterface $defaultConfig, array $config = [])
    {
        $this->config = array_merge($defaultConfig->get('adyen'), $config);
        
        // Since this class is tighly coupling to Adyen PHP library
        // I am initiating these classes here instead of injecting from outside
        // since this 'AdyenClient' class should handle all initiate connection by itself, and
        // we would not swap these Adyen classes with other driver
        $client = new \Adyen\Client();
        $client->setUsername($this->config['username']);
        $client->setPassword($this->config['password']);
        $client->setEnvironment($this->config['environment']);
        $client->setApplicationName($this->config['appname']);
        $this->service = new \Adyen\Service\Payment ($client);
    }

    /**
     * For injecting mocked object for unit test
     * 
     * @param Adyen\Service\Payment $service
     */
    public function setAdyenPaymentService (\Adyen\Service\Payment $service)
    {
        $this->service = $service;
    }

    /**
     * @param Oadtz\Checkout\PaymentInfo $paymentInfo
     * 
     * @return Oadtz\Checkout\PaymentResult
     */
    public function authorise(PaymentInfo $paymentInfo) {
        $paymentData = [
            "card"  => [
                "number"        => $paymentInfo->getCardNumber() ,
                "expiryMonth"   => $paymentInfo->getCardExpiryDate()->format('m'),
                "expiryYear"    => $paymentInfo->getCardExpiryDate()->format('Y'),
                "cvc"           => $paymentInfo->getCardCVV(),
                "holderName"    => $paymentInfo->getCardHolderName()
            ],
            "amount"            => [
                "value"           => $paymentInfo->getAmount(),
                "currency"        => $paymentInfo->getCurrency()
            ],
            "reference"         => $paymentInfo->getSupplementData()['reference'] ?? '',
            "merchantAccount"   => $this->config['merchant_account'],
            "additionalData"    =>  $paymentInfo->getSupplementData()['additionalData'] ?? [
                "card.encrypted.json" =>  null
            ]
        ];

        try {
            $response = $this->service->authorise($paymentData);

            // This is for storing result from payment gateway API and the class did not implement from any interface so I am not dependency injecting here
            return new PaymentResult([
                'success'   =>  ($response['resultCode'] ?? null) == 'Authorised',
                'paymentGateway'    =>  'adyen',
                'responseData'  =>  $response
            ]);
        } catch (\Exception $e) {
            throw new \Oadtz\Checkout\Exceptions\PaymentFailedException($e->getMessage());
        }
    }
}