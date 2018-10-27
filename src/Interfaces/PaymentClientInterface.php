<?php
namespace Oadtz\Checkout\Interfaces;

interface PaymentClientInterface {
    //public function setConfig(array $config);
    public function authorise(array $paymentData);
}