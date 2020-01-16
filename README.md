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

// Creating a new payment object ($merchant_id, $shop_id)
$payment = new \Starkpayments\Payment('<merchant id>', '<shop id>');

// Retrieving payment url ($orderid, $amount)
$redirect_url =  $payment->getUrl(15333, 23.5);
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