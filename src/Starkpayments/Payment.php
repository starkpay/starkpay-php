<?php

/**
 *
 * Starkpayments
 * 
 * Integrated crypto payments for global businesses
 *
 * @copyright      MIT
 * @author         Stark Payment https://github.com/starkpay
 *
 */


namespace Starkpayments;

/**
 * Payment Class
 * @package Starkpayments
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
     * @var string Error Message
     */
    private $error;

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

    /**
     * paymentIsValid
     *
     * To get the payment is valid or not.Need to call the function in call back url
     *
     * @return [bool] $auth_ok
     * 
     */
    public function paymentIsValid()
    {
        $this->error = "Unknown error!";
        $auth_ok = false;

        if (isset($_POST['ipn_mode']) && $_POST['ipn_mode'] == 'hmac') {
            if (isset($_SERVER['HTTP_HMAC']) && !empty($_SERVER['HTTP_HMAC'])) {
                $request = file_get_contents('php://input');
                if ($request !== FALSE && !empty($request)) {
                    if (isset($_POST['merchant']) && $_POST['merchant'] == trim($this->merchant_id)) {
                        $hmac = hash_hmac("sha512", $request, trim($this->secret));
                        if ($hmac == $_SERVER['HTTP_HMAC']) {
                            $auth_ok = true;
                        } else {
                            $this->error = 'HMAC signature does not match';
                        }
                    } else {
                        $this->error = 'No or incorrect Merchant ID passed';
                    }
                } else {
                    $this->error = 'Error reading POST data';
                }
            } else {
                $this->error = 'No HMAC signature sent.';
            }
        } else {
            if (isset($_POST['PHP_MERCHANT_ID']) && isset($_POST['PHP_SHOP_ID']) && 
                $_POST['PHP_MERCHANT_ID'] == urlencode(trim($this->merchant_id)) && 
                $_POST['PHP_SHOP_ID'] == urlencode(trim($this->shop_id))) {
                $auth_ok = true;
            } else {
                 $this->error = "Invalid merchant id/ipn secret";
            }
        }
        return $auth_ok;
    }

    /**
     * getErrorMessage
     *
     * To get the last error message
     *
     * @return [string] $error
     * 
     */
    public function getErrorMessage()
    {
        return $this->error;
    }
}
