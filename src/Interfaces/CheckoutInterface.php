<?php
namespace Oadtz\Checkout\Interfaces;

interface CheckoutInterface
{
    public function __construct (PaymentInterface $paymant);
    public function processPayment(array $paymentData);
}
