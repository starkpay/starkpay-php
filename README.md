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
$payment = new \Starkpayment\Payment(233, 1);

// Retrieving payment url ($orderid, $amount)
$redirect_url =  $payment->getUrl(15333, 23.5);
?>
```

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.