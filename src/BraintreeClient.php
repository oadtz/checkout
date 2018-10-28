<?php
namespace Oadtz\Checkout;

use Oadtz\Checkout\Interfaces\{ConfigInterface, PaymentClientInterface};
use Oadtz\Checkout\{PaymentInfo, PaymentResult};

class BraintreeClient implements PaymentClientInterface {
    protected $config, $service;

    /**
     * @param ConfigInterface $defaultConfig
     * @param array $config = []
     */
    public function __construct (ConfigInterface $defaultConfig, array $config = [])
    {
        $this->config = array_merge($defaultConfig->get('braintree'), $config);
        $this->paymentData = [];
        
        // Since this class is tighly coupling to Braintree PHP library
        // I am initiating these classes here instead of injecting from outside
        // since this 'BraintreeClient' class should handle all initiate connection by itself, and
        // we would not swap these Braintree classes with other driver
        $this->service = new \Braintree\Gateway([
            'environment'       => $this->config['environment'],
            'merchantId'        => $this->config['merchant_id'],
            'publicKey'         => $this->config['public_key'],
            'privateKey'        => $this->config['private_key']
        ]);
    }

    /**
     * For injecting mocked object for unit test
     * 
     * @param Braintree\Gateway $service
     */
    public function setBraintreePaymentService (\Braintree\Gateway $service)
    {
        $this->service = $service;
    }

    /**
     * @param Oadtz\Checkout\PaymentInfo $data
     * 
     * @return Oadtz\Checkout\PaymentResult
     */
    public function authorise(PaymentInfo $paymentInfo) {
        $paymentData = [
            "creditCard"  => [
                "cardholderName"    => $paymentInfo->getCardHolderName(),
                "number"            => $paymentInfo->getCardNumber(),
                "expirationDate"    => $paymentInfo->getCardExpiryDate()->format('m/Y'),
                "cvv"               => $paymentInfo->getCardCVV()
            ],
            "amount"                => $paymentInfo->getAmount()
        ];
        if (isset($paymentInfo->getSupplementData()['options']))
            $paymentData["options"] = $paymentInfo->getSupplementData()['options'];
        if (isset($paymentInfo->getSupplementData()['paymentMethodNonce']))
            $paymentData["paymentMethodNonce"] = $paymentInfo->getSupplementData()['paymentMethodNonce'];

        try {
            $response = $this->service->transaction()->sale($paymentData);

            // This is for storing result from payment gateway API and the class did not implement from any interface so I am not dependency injecting here
            return new PaymentResult([
                'success'   =>  $response->success,
                'paymentGateway'    =>  'braintree',
                'responseData'  =>  $response
            ]);
        } catch (\Exception $e) {
            throw new \Oadtz\Checkout\Exceptions\PaymentFailedException($e->getMessage());
        }
    }
}