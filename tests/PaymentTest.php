<?php

/**
 *
 * Starkpayment Test Case
 * 
 * Integrated crypto payments for global businesses
 *
 * @copyright      MIT
 * @author         Stark Payment https://github.com/starkpay
 *
 */

/**
 * Payment_Test Class
 * @package Starkpayments
 */

use PHPUnit\Framework\TestCase;

class Payment_Test extends TestCase
{
    public function testPayment()
    {
        $payment = new \Starkpayment\Payment('123', '456');
        $this->assertInstanceOf('Starkpayment\Payment', $payment);
        $this->assertInternalType('string', $payment->getUrl('12',23.4));
        $this->assertRegexp('/starkpayments.net/', $payment->getUrl('12',23.4));
    }
}
