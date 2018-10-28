<?php
namespace Oadtz\Checkout;

class PaymentInfo {
    private $paymentData = [
        'card_holder_name'          => '',
        'card_number'               => '',
        'card_expiry_date'          => '',
        'card_cvv'                  => '',
        'amount'                    => 0.00,
        'currency'                  => 'USD',
        'supplement_data'           => []
    ];

    /**
     * @param string $name
     */
    public function setCardHolderName (string $name)
    {
        $this->paymentData['card_holder_name'] = $name;
    }

    /**
     * @return string
     */
    public function getCardHolderName ()
    {
        return $this->paymentData['card_holder_name'];
    }

    /**
     * @param string $ccNo
     */
    public function setCardNumber (string $ccNo)
    {
        $this->paymentData['card_number'] = $ccNo;
    }

    /**
     * @return string
     */
    public function getCardNumber ()
    {
        return $this->paymentData['card_number'];
    }

    /**
     * @param string $cvv
     */
    public function setCardCVV (string $cvv)
    {
        $this->paymentData['card_cvv'] = $cvv;
    }

    /**
     * @return string
     */
    public function getCardCVV ()
    {
        return $this->paymentData['card_cvv'];
    }

    /**
     * @param DateTime $date
     */
    public function setCardExpiryDate (\DateTime $date)
    {
        $this->paymentData['card_expiry_date'] = $date;
    }

    /**
     * @return DateTime
     */
    public function getCardExpiryDate ()
    {
        if (get_class($this->paymentData['card_expiry_date']) !== 'DateTime')
            return new \DateTime((string)$this->paymentData['card_expiry_date']);

        return $this->paymentData['card_expiry_date'];
    }

    /**
     * @param float $amount
     */
    public function setAmount (float $amount)
    {
        $this->paymentData['amount'] = $amount;
    }

    /**
     * @return float
     */
    public function getAmount ()
    {
        return $this->paymentData['amount'];
    }

    /**
     * @param string $currency
     */
    public function setCurrency (string $currency)
    {
        $this->paymentData['currency'] = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency ()
    {
        return $this->paymentData['currency'];
    }

    /**
     * @param array $data
     */
    public function setSupplementData (array $data)
    {
        $this->paymentData['supplement_data'] = $data;
    }

    /**
     * @return array
     */
    public function getSupplementData ()
    {
        return (array)$this->paymentData['supplement_data'];
    }
}