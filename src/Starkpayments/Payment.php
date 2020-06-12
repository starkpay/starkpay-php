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
    public $endpoint = 'https://pay.starkpayments.net/api/payment';

    /**
     * @var string encryption method
     */
    private $encryptionMethod = 'AES-256-CBC';

    /**
     * @var string API Key
     */
    private $api_key;

    /**
     * @var string Error Message
     */
    private $error;

    /**
     * __construct
     * @param [string] $api_key API Key
     */
    function __construct($key)
    {
        $this->api_key = trim($key);
    }


    private function request($data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Api-Key: '. $this->api_key));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        return json_decode($response, true);
    }

    /**
     * getURL
     *
     * To get payment URL
     *
     * @param [float] $amount Amount
     * @param [string] $currency Currency
     * @param [string] $description Descripton
     * @param [string] $redirectUrl Redirect URL
     * 
     */
    public function payment($amount, $currency, $description, $redirectUrl)
    {
        $response = $this->request([
            'amount' => $amount,
            'currency' => $currency,
            'description' => $description,
            'redirectUrl' => $redirectUrl
        ]);
        return new Pay($response);
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
