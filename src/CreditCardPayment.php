<?php
namespace Oadtz\Checkout;

use Oadtz\Checkout\{AdyenClient, BraintreeClient, PaymentInfo};
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
     * @param \Oadtz\Checkout\PaymentInfo $paymentInfo
     * 
     * @return \Oadtz\Checkout\PaymentResult
     */
    public function pay (PaymentInfo $paymentInfo)
    {
        $paymentClient = $this->braintreeClient;
        $currency = $paymentInfo->getCurrency();
        $creditcardNo = $paymentInfo->getCardNumber();

        if ($this->isAmex ($creditcardNo) && strtoupper($currency) != 'USD')
            throw new \Oadtz\Checkout\Exceptions\PaymentFailedException ('AMEX is possible to use only for USD');
        
        if ($this->isAmex ($creditcardNo) || in_array(strtoupper($currency), ['USD', 'EUR', 'AUD']))
            $paymentClient = $this->adyenClient;

        return $this->authorise($paymentClient, $paymentInfo);
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
     * @param  \Oadtz\Checkout\Interfaces\PaymentClientInterface $client
     * @param \Oadtz\Checkout\PaymentInfo $paymentInfo
     * 
     * @return \Oadtz\Checkout\PaymentStatus
     */
    protected function authorise (PaymentClientInterface $client, PaymentInfo $paymentInfo) 
    {
        return $client->authorise ($paymentInfo);
    }

}