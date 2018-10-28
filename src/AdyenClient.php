<?php
namespace Oadtz\Checkout;

use Oadtz\Checkout\Interfaces\{ConfigInterface, PaymentClientInterface};
use Oadtz\Checkout\PaymentResult;

class AdyenClient implements PaymentClientInterface {
    protected $config, $service;
    protected $paymentData = [
        "card"  => [
            "number"        => null,
            "expiryMonth"   => null,
            "expiryYear"    => null,
            "cvc"           => null,
            "holderName"    => null
        ],
        "amount"            => [
          "value"           => 0,
          "currency"        => "USD"
        ],
        "reference"         => null,
        "merchantAccount"   => null,
        "additionalData"    =>  [
            "card.encrypted.json" =>  null
        ]
    ];

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
     * @param array $data
     * 
     * @return Oadtz\Checkout\PaymentResult
     */
    public function authorise(array $paymentData) {
        $paymentData = array_merge($this->paymentData, $paymentData);
        try {
            $response = $this->service->authorise($data);

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