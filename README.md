# starkpay-php

Integrated crypto payments for global businesses

https://starkpayments.com/

## Requirements

PHP 5.3.0 and later.

## Installation
Via Composer
``` bash
composer require starkpay-php/starkpay
```

### Basic Usage

```php
<?php
// including autoload file.
require __DIR__.'/vendor/autoload.php';

// Creating a new payment object ($apiKey) visit http://dashboard.starkpayments.net to get API Key
$payment = new \Starkpayments\Payment('<api_key>');

// Get pay object ($amount, $currency (EUR,USD etc), $description, $returnUrl)
$pay =  $payment->getUrl(23.5, 'USD', 'Invoice 2223', 'https://mydomain.com/payment_return.php');

if ($pay->isSuccess()) {
	//redirect URL
	 $redirect_url = $pay->getRedirectUrl();
	 header("location: $redirect_url");
} else {
	echo $pay->getErrorMessage();
}

?>
```

### Payment Validation On CallBack Page

```php
<?php
if ($payment->paymentIsValid()) {
	echo "Payment Successful";
} else {
	echo "Payment Failed, Error : ". $payment->getErrorMessage();
}
?>
```

### PHP Unit
PHP unit test
``` bash
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/ --coverage-html reports --whitelist src
```

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.