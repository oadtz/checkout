<?php
namespace Oadtz\Checkout\Interfaces;

interface PaymentClientInterface {
    public function __construct (ConfigInterface $defaultConfig, array $config = []);
    public function authorise(array $paymentData);
}