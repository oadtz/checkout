<?php
namespace Oadtz\Checkout;

use Oadtz\Checkout\Interfaces\{ConfigInterface, CheckoutInterface, PaymentInterface};

class Checkout implements CheckoutInterface
{

    /**
     * @var  \Oadtz\Checkout\Config
     */
    private $config;

    /**
     * Sample constructor.
     *
     * @param \Oadtz\Checkout\Config $config
     */
    public function __construct(ConfigInterface $defaultConfig, array $config)
    {
        $this->config = array_merge($defaultConfig->get(), $config);
    }

    /**
     * @param array $paymentData
     *
     * @return  string
     */
    public function processPayment(PaymentInterface $payment, array $paymentData)
    {
        $payment->setConfig($this->config);

        return $payment->pay($paymentData);
    }

}
