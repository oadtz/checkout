<?php
namespace Oadtz\Checkout;

use Oadtz\Checkout\Interfaces\{ConfigInterface, CheckoutInterface, PaymentInterface};
use Oadtz\Checkout\PaymentInfo;

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
     * @param Oadtz\Checkout\PaymentInfo $paymentInfo
     *
     * @return  string
     */
    public function processPayment(PaymentInfo $paymentInfo)
    {
        return $this->payment->pay($paymentInfo);
    }

}
