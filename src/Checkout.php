<?php
namespace Oadtz\Checkout;

use Oadtz\Checkout\Interfaces\{ConfigInterface, CheckoutInterface, PaymentInterface};
use Oadtz\Checkout\PaymentInfo;

class Checkout implements CheckoutInterface
{
    protected $payment, $paymentInfo;

    /**
     * @param Oadtz\Checkout\Interfaces\PaymentInterface $payment
     */
    public function __construct (PaymentInterface $payment)
    {
        $this->payment = $payment;
        $this->paymentInfo = new PaymentInfo();
    }
    
    /**
     * @param string $name
     */
    public function setCardHolderName (string $name)
    {
        $this->paymentInfo->setCardHolderName ($name);
    }


    /**
     * @param string $ccNo
     */
    public function setCardNumber (string $ccNo)
    {
        $this->paymentInfo->setCardNumber ($ccNo);
    }


    /**
     * @param string $cvv
     */
    public function setCardCVV (string $cvv)
    {
        $this->paymentInfo->setCardCVV ($cvv);
    }

    /**
     * @param DateTime $date
     */
    public function setCardExpiryDate (\DateTime $date)
    {
        $this->paymentInfo->setCardExpiryDate ($date);
    }

    /**
     * @param float $amount
     */
    public function setAmount (float $amount)
    {
        $this->paymentInfo->setAmount ($amount);
    }

    /**
     * @param string $currency
     */
    public function setCurrency (string $currency)
    {
        $this->paymentInfo->setCurrency ($currency);
    }

    /**
     * @param array $data
     */
    public function setSupplementData (array $data)
    {
        $this->paymentInfo->setSupplementData ($data);
    }

    /**
     * @param Oadtz\Checkout\PaymentInfo $paymentInfo
     *
     * @return  string
     */
    public function processPayment()
    {
        return $this->payment->pay($this->paymentInfo);
    }

}
