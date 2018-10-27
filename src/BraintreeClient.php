<?php
namespace Oadtz\Checkout;

use Oadtz\Checkout\Interfaces\{ConfigInterface, PaymentClientInterface};
use Oadtz\Checkout\PaymentResult;

class BraintreeClient implements PaymentClientInterface {
    protected $config, $paymentData;

    /**
     * @param ConfigInterface $defaultConfig
     * @param array $config = []
     */
    public function __construct (ConfigInterface $defaultConfig, array $config = [])
    {
        $this->config = array_merge($defaultConfig->get('adyen'), $config);
        $this->paymentData = [];
    }



    /**
     * @param array $data
     * 
     * @return Oadtz\Checkout\PaymentResult
     */
    public function authorise(array $paymentData) {
        $paymentData = array_merge($this->paymentData, $paymentData);
        try {
            //$response = $this->service->authorise($paymentData);

            // This is for storing result from payment gateway API and the class did not implement from any interface so I am not dependency injecting here
            return new PaymentResult([
                'success'   =>  ($response['resultCode'] ?? null) == 'Authorised',
                'paymentGateway'    =>  'braintree',
                'responseData'  =>  $response
            ]);
        } catch (\Exception $e) {
            throw new \Oadtz\Checkout\Exceptions\PaymentFailedException($e->getMessage());
        }
    }
}