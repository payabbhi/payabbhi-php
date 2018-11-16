# Payabbhi PHP library
[![license](https://poser.pugx.org/payabbhi/payabbhi-php/license)](https://packagist.org/packages/payabbhi/payabbhi-php)
[![Packagist](https://poser.pugx.org/payabbhi/payabbhi-php/downloads)](https://packagist.org/packages/payabbhi/payabbhi-php)
[![Packagist](https://poser.pugx.org/payabbhi/payabbhi-php/v/stable.svg)](https://packagist.org/packages/payabbhi/payabbhi-php)

Make sure you have signed up for your [Payabbhi Account](https://payabbhi.com/docs/account) and downloaded the [API keys](https://payabbhi.com/docs/account/#api-keys) from the [Portal](https://payabbhi.com/portal).


## Requirements

PHP 5.3.3 and later.

## Composer

The library can be installed via [Composer](http://getcomposer.org/). Run the following command:

```bash
$ composer require payabbhi/payabbhi-php
```

Start using the library as per Composer [autoloading](https://getcomposer.org/doc/01-basic-usage.md#autoloading):
simply include `vendor/autoload.php`, available as part of library installation via Composer.

```php
require_once('vendor/autoload.php');
```

## Manual Installation

In case Composer is not used, download the latest release from [Releases](https://github.com/payabbhi/payabbhi-php/releases). 
For using the manually downloaded library, simply include `init.php` in your code.

```php
require_once('/path/to/payabbhi-php/init.php');
```

For manual installation, make sure that the [dependencies](#dependencies) are resolved. 

## Documentation

Please refer to:
- [PHP Lib Docs](https://payabbhi.com/docs/api/?php)
- [Integration Guide](https://payabbhi.com/docs/integration)

## Dependencies

The library requires the following extensions:

- [`curl`](https://secure.php.net/manual/en/book.curl.php)
- [`json`](https://secure.php.net/manual/en/book.json.php)

In case of manual installation, make sure that the dependencies are resolved.

## Getting Started

### Authentication

```php
// Set your credentials
$client = new \Payabbhi\Client('access_id', 'secret_key');

// Optionally set your app info.
// app_version and app_url are optional

$client->setAppInfo('app_name','app_version','app_url');
```

### Orders

```php

// Create order
$order = $client->order->create(array('merchant_order_id' => $merchantOrderID,
                                      'amount' => $amount,
                                      'currency' => $currency));

// Retrieve a particular order object
$order = $client->order->retrieve($orderId);

// Retrieve a set of order objects based on given filter params
$orders = $client->order->all(array('count' => 2));

// Retrieve a set of payments for a given order
$payments = $client->order->retrieve($orderId)->payments();
```

### Payments

```php
// Retrieve all payments
$payments = $client->payment->all();

// Retrieve a particular payment
$payment = $client->payment->retrieve($id);

// Capture a payment
$payment->capture();

// Fully Refund a payment
$refund = $payment->refund();

// Retrieve a set of refund objects for a given payment with optional filter params
$refunds = $payment->refunds();
```


### Refunds

```php
// Create a refund
$fullRefund = $client->refund->create($paymentID);

// Create a partial refund
$partialRefund = $client->refund->create($paymentID, array('amount'=>$refundAmount));

// Retrieve a set of orders with the given filter params
$refunds = $client->refund->all(array('count' => 2));

// Retrieve a particular refund object
$refund = $client->refund->retrieve($refundId);
```

### Verifying payment signature

```php
$attributes = array(
                    'payment_id'        => $payment_id,
                    'order_id'          => $order_id,
                    'payment_signature' => $payment_signature
                   );
$client->utility->verifyPaymentSignature($attributes);

```

### Verifying webhook signature

```php
$client->utility->verifyWebhookSignature($payload,$actualSignature,$secret);

// replayInterval is optional
$client->utility->verifyWebhookSignature($payload,$actualSignature,$secret,$replayInterval);

```

### Customers

Refer to [PHP Lib Docs](https://payabbhi.com/docs/api/?php)

### Invoices

Refer to [PHP Lib Docs](https://payabbhi.com/docs/api/?php)

### Subscriptions, Products, Plans

Refer to [PHP Lib Docs](https://payabbhi.com/docs/api/?php)

### Transfers

Refer to [PHP Lib Docs](https://payabbhi.com/docs/api/?php)


## Development

Install dependencies:

``` bash
$ composer install
```

## Tests

Install dependencies as mentioned above (which will resolve [PHPUnit](http://packagist.org/packages/phpunit/phpunit)), set ACCESS_ID and SECRET_KEY as environment variable then you can run the test suite:

```bash
$ export ACCESS_ID="<access-id>"
$ export SECRET_KEY="<secret-key>"
$ ./vendor/bin/phpunit
```

Or to run an individual test file:

```bash
$ export ACCESS_ID="<access-id>"
$ export SECRET_KEY="<secret-key>"
$ ./vendor/bin/phpunit tests/PaymentTest.php
```
