<?php

/**
 *
 * Starkpayment
 * 
 * Integrated crypto payments for global businesses
 *
 * @copyright      MIT
 * @author         Stark Payment https://github.com/starkpay
 *
 */


namespace Starkpayment;

/**
 * Payment Class
 * @package Starkpayment
 */

class Payment
{

    /**
     * @var string api endpoint
     */
    public $endpoint = 'https://process.starkpayments.net/gateway/checkout';

    /**
     * @var string encryption method
     */
    private $encryptionMethod = 'AES-256-CBC';

    /**
     * @var string encryption method
     */
    private $secret = 'Mystarkpayment_@#%09keypasswordf';    

    /**
     * @var string Merchant ID
     */
    private $merchant_id;

    /**
     * @var string Shop ID
     */
    private $shop_id;

    /**
     * __construct
     * @param [string] $merchant_id Merchant ID
     * @param [string] $shop_id Shop ID
     */
    function __construct($merchant_id, $shop_id)
    {
        $this->merchant_id = urlencode($merchant_id);
        $this->shop_id = urlencode($shop_id);
    }

    /**
     * encrypt
     * @param [string] $data
     * @return [string] $encrypted data
     */
    private function encrypt($data)
    {
        $iv = substr($this->secret, 0, 16);
        return urlencode(openssl_encrypt($data, $this->encryptionMethod, $this->secret,0,$iv));
    }

    /**
     * getURL
     *
     * To get payment URL
     *
     * @param [string] $merchant_id Merchant ID
     * @param [string] $shop_id Shop ID
     * @param [string] $shop_id Shop ID
     * 
     */
    public function getURL($order_id , $amount, $currency = 'GBP') : string
    {
        return join('/',[
            $this->endpoint,
            $this->merchant_id,
            $this->shop_id,
            $this->encrypt($order_id),
            $this->encrypt($amount),
            $currency
        ]);
    }
}
