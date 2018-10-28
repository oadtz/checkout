<?php
namespace Oadtz\Checkout\Interfaces;

use Oadtz\Checkout\PaymentInfo;

interface PaymentInterface {
    public function pay (PaymentInfo $paymentInfo);
}