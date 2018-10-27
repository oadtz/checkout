<?php
namespace Oadtz\Checkout;

class PaymentResult {
    protected $data = [];

    public function __construct (array $data = null)
    {
        if (!is_null ($data))
        {
            $this->setSuccess((bool)($data['success'] ?? false));
            $this->setPaymentGateway($data['paymentGateway'] ?? null);
            $this->setResponseData($data['responseData'] ?? null);
        }
    }
    
    /**
     * @param bool $success
     */
    public function setSuccess (bool $success) 
    {
        $this->data['success'] = $success;
    }

    
    /**
     * @param string $paymentGateway
     */
    public function setPaymentGateway (string $paymentGateway = null) 
    {
        $this->data['paymentGateway'] = $paymentGateway;
    }


    /**
     * @param mixed $responseData
     */
    public function setResponseData ($responseData)
    {
        $this->data['responseData'] = $responseData;
    }

    /**
     * @return bool
     */
    public function getSuccess ()
    {
        return $this->data['success'] ?? false;
    }

    /**
     * @return string
     */
    public function getPaymentGateway ()
    {
        return $this->data['paymentGateway'] ?? null;
    }

    /**
     * @return mixed
     */
    public function getResponseData ()
    {
        return $this->data['responseData'] ?? null;
    }
 
}