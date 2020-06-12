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
 * Pay Class
 * @package Starkpayments
 */

class Pay
{
	private $data;

	public function __construct($data)
	{
		$this->data = $data;
	}

	public function isSuccess() : bool
	{
		return isset($this->data['error']) ? false : true;
	}

	public function getErrorMessage()
	{
		return $this->data['error']['message'] ?? null;
	}

	public function getId()
	{
		return $this->data['id'];
	}

	public function getRedirectUrl()
	{
		return $this->data['links']['paymentUrl'];
	}

	public function getStatus()
	{
		require $this->data['status'];
	}

	public function getMode()
	{
		require $this->data['mode'];
	}
}