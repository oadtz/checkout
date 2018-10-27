<?php
namespace Oadtz\Checkout;

use Oadtz\Checkout\Interfaces\PaymentClientInterface;
use Oadtz\Checkout\PaymentResult;

class BraintreeClient implements PaymentClientInterface {
    protected $service;
    protected $paymentData;

    /**
     * @param Adyen\Service\Payment $service, array $options
     */
    public function __construct (\Adyen\Service\Payment $service)
    {
        $this->service = $service;

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
            $response = $this->service->authorise($paymentData);

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