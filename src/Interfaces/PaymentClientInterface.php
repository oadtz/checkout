<?php
namespace Oadtz\Checkout\Interfaces;

use Oadtz\Checkout\PaymentInfo;

interface PaymentClientInterface {
    public function __construct (ConfigInterface $defaultConfig, array $config = []);
    public function authorise(PaymentInfo $paymentInfo);
}