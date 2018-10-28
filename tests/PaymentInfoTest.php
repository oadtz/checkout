<?php
namespace Oadtz\Checkout\Tests;

use Oadtz\Checkout\PaymentInfo;
use PHPUnit\Framework\TestCase;

class PaymentInfoTest extends TestCase {
    protected $paymentInfo;

    public function setUp ()
    {
        parent::setUp ();

        $this->paymentInfo = new PaymentInfo();
    }

    public function tearDown () 
    {
        unset ($this->paymentInfo);
    }

    public function testGetSetCardHolderName ()
    {
        $input = 'Thanapat Pirmphol';
        $this->paymentInfo->setCardHolderName($input);
        $this->assertEquals ($input, $this->paymentInfo->getCardHolderName($input));
    }

    public function testGetSetCardNumber ()
    {
        $input = '4111111111111111';
        $this->paymentInfo->setCardNumber($input);
        $this->assertEquals ($input, $this->paymentInfo->getCardNumber($input));
    }

    public function testGetSetCardCVV ()
    {
        $input = '232';
        $this->paymentInfo->setCardCVV($input);
        $this->assertEquals ($input, $this->paymentInfo->getCardCVV($input));
    }

    public function testGetSetCardExpiryDate ()
    {
        $input = new \DateTime('2020-01-01');
        $this->paymentInfo->setCardExpiryDate($input);
        $this->assertEquals ($input, $this->paymentInfo->getCardExpiryDate($input));
    }

    public function testGetSetAmount ()
    {
        $input = 100.00;
        $this->paymentInfo->setAmount($input);
        $this->assertEquals ($input, $this->paymentInfo->getAmount($input));
    }

    public function testGetSetCurrency ()
    {
        $input = 'USD';
        $this->paymentInfo->setCurrency($input);
        $this->assertEquals ($input, $this->paymentInfo->getCurrency($input));
    }

    public function testGetSetSupplementData ()
    {
        $input = [ 'dummy' => true ];
        $this->paymentInfo->setSupplementData($input);
        $this->assertSame ($input, $this->paymentInfo->getSupplementData($input));
    }

}