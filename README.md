
<p align="center">
  <img src="https://user-images.githubusercontent.com/7081446/178473472-c0c29ec5-762c-47de-9ed4-999e5ad6c70d.png" width="200px" position="center">
</p>

# Buckaroo PHP SDK
[![Latest release](https://badgen.net/github/release/buckaroo-it/BuckarooSDK_PHP)](https://github.com/buckaroo-it/BuckarooSDK_PHP/releases)

<p align="center">
  <img src="https://www.buckaroo.nl/media/3613/buckaroo-payment.png" width="500px" position="center">
</p>

---
### Index
- [About](#about)
- [Requirements](#requirements)
- [Composer Installation](#composer-installation)
- [Requirements](#requirements)
- [Example](#example)
- [Contribute](#contribute)
- [Versioning](#versioning)
- [Additional information](#additional-information)
---

### About

Buckaroo is the the Payment Service Provider for all your online payments with more than 15,000 companies relying on Buckaroo's platform to securely process their payments, subscriptions and unpaid invoices.
Buckaroo developed their own PHP SDK. The SDK is a modern, open-source PHP library that makes it easy to integrate your PHP application with Buckaroo's services.
Start accepting payments today with Buckaroo.

### Requirements

To use the Buckaroo API client, the following things are required:

+ A Buckaroo account ([Dutch](https://www.buckaroo.nl/start) or [English](https://www.buckaroo.eu/solutions/request-form))
+ PHP >= 7.4
+ Up-to-date OpenSSL (or other SSL/TLS toolkit)

### Composer Installation

By far the easiest way to install the Buckaroo API client is to require it with [Composer](http://getcomposer.org/doc/00-intro.md).

    $ composer require buckaroo/sdk:^1.0

    {
        "require": {
            "buckaroo/sdk": "^1.0"
        }
    }

### Example
Create and config the Buckaroo object. 
You can find your credentials in plaza  [WEBSITE_KEY](https://plaza.buckaroo.nl/Configuration/Website/Index/) and [SECRET_KEY](https://admin.buckaroo.nl/Configuration/Merchant/SecretKey)

```php
require __DIR__ . '/vendor/autoload.php';

# Get your website & secret key in your plaza.
# You can perform a test payment by giving the third param with the string "test", on default it is set on "live"
$buckaroo = new \BuckarooClient('WEBSITE_KEY', 'SECRET_KEY', 'test');
```

Create a payment with all the available payment methods. In this example, we show how to create a credit card payment. Each payment has a slightly different payload.
```php
# Create a new payment
$buckaroo->method('creditcard') // Input the desire payment method.
    ->pay([
        'name'          => 'visa', // Request to pay with Visa
        'amountDebit'   => 10, // The amount we want to charge
        'invoice'       => 'UNIQUE-INVOICE-NO', // Each payment must contain a unique invoice number
    ]);
```

After you create a transaction, you can retrieve several transaction information on demand.
```php
# Create a new payment
$transaction = $buckaroo->transaction('YOUR-TRANSACTION-KEY')

$transaction->status(); // Retrieve transaction status
$transaction->refundInfo(); // Retrieve refund info
$transaction->cancelInfo() // Retrieve cancellation info
```

Find our full documentation online on [dev.buckaroo.nl](https://dev.buckaroo.nl/).

### Contribute

We really appreciate it when developers contribute to improve the Buckaroo plugins.
If you want to contribute as well, then please follow our [Contribution Guidelines](CONTRIBUTING.md).

### Versioning
<p align="left">
  <img src="https://user-images.githubusercontent.com/7081446/178474134-f4c3976d-653c-4ca1-bcd1-48bf6d489196.png" width="500px" position="center">
</p>

- **MAJOR:** Breaking changes that require additional testing/caution
- **MINOR:** Changes that should not have a big impact
- **PATCHES:** Bug and hotfixes only

### Additional information
- **Support:** https://support.buckaroo.eu/contact
- **Contact:** [support@buckaroo.nl](mailto:support@buckaroo.nl) or [+31 (0)30 711 50 50](tel:+310307115050)

## License
Buckaroo PHP SDK is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
