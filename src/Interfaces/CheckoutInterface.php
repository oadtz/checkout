<?php
namespace Oadtz\Checkout\Interfaces;

use Oadtz\Checkout\PaymentInfo;

interface CheckoutInterface
{
    public function __construct (PaymentInterface $payment);
    public function processPayment();
}
