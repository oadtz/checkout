<?php
namespace Oadtz\Checkout\Interfaces;

interface CheckoutInterface
{
    public function processPayment(PaymentInterface $paymant, array $paymentData);
}
