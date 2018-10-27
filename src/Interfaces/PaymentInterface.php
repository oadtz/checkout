<?php
namespace Oadtz\Checkout\Interfaces;

interface PaymentInterface {
    public function setConfig (array $config);
    public function pay (array $data);
}