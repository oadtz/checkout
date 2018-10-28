<?php
namespace Oadtz\Checkout;

use Oadtz\Checkout\Interfaces\{ConfigInterface, CheckoutInterface, PaymentInterface};

class Checkout implements CheckoutInterface
{
    protected $payment;

    /**
     * @param Oadtz\Checkout\Interfaces\PaymentInterface $payment
     */
    public function __construct (PaymentInterface $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @param array $paymentData
     *
     * @return  string
     */
    public function processPayment(array $paymentData)
    {
        return $this->payment->pay($paymentData);
    }

}
