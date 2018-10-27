<?php
namespace Oadtz\Checkout;

use Oadtz\Checkout\{AdyenClient, BraintreeClient};
use Oadtz\Checkout\Interfaces\{PaymentInterface, PaymentClientInterface};

class CreditCardPayment implements PaymentInterface {
    protected $adyenClient, $braintreeClient;

    /**
     * @param AdyenClient $adyenClient
     * @param BraintreeClient $braintreeClient
     */
    public function __construct (AdyenClient $adyenClient, BraintreeClient $braintreeClient) {
        $this->adyenClient = $adyenClient;
        $this->braintreeClient = $braintreeClient;
    }

    /**
     * @param array $config
     */
    public function setConfig (array $config)
    {
        //$this->adyenClient->setConfig($config['adyen']);
        //$this->braintreeClient->setConfig($config['braintree']);
    }

    /**
     * @param array $paymentData
     * 
     * @return \Oadtz\Checkout\PaymentResult
     */
    public function pay (array $paymentData)
    {
        $paymentClient = $this->braintreeClient;
        $currency = $paymentData['currency'] ?? '';
        $creditcardNo = $paymentData['creditcard_number'] ?? '';

        if ($this->isAmex ($creditcardNo) && strtoupper($currency) != 'USD')
            throw new \Oadtz\Checkout\Exceptions\PaymentFailedException ('AMEX is possible to use only for USD');
        
        if ($this->isAmex ($creditcardNo) || in_array(strtoupper($currency), ['USD', 'EUR', 'AUD']))
            $paymentClient = $this->adyenClient;

        return $this->authorise($paymentClient, $paymentData);
    }

    /**
     * @param string $ccNo
     * 
     * @return bool
     */
    protected function isAmex (string $ccNo)
    {
        return preg_match("/^3$|^3[47][0-9]{0,13}$/i", $ccNo);
    }

    /**
     * @param PaymentClientInterface $client, array $paymentData
     * 
     * @return \Oadtz\Checkout\PaymentStatus
     */
    protected function authorise (PaymentClientInterface $client, array $paymentData) 
    {
        return $client->authorise ($paymentData);
    }

}