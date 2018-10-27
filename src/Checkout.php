<?php
namespace Oadtz\Checkout;

use Oadtz\Checkout\Interfaces\{ConfigInterface, CheckoutInterface, PaymentInterface};

class Checkout implements CheckoutInterface
{
    /**
     * @param array $paymentData
     *
     * @return  string
     */
    public function processPayment(PaymentInterface $payment, array $paymentData)
    {
        return $payment->pay($paymentData);
    }

}
