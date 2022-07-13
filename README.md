<p align="center">
  <img src="https://user-images.githubusercontent.com/7081446/178473472-c0c29ec5-762c-47de-9ed4-999e5ad6c70d.png" width="200px" position="center">
</p>

# Buckaroo PHP SDK

<p align="center">
  <img src="https://user-images.githubusercontent.com/7081446/178476892-372b4f2b-f74d-4b00-b174-4fa1d5a468e5.png" width="500px" position="center">
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

    $ composer require buckaroo/buckaroo-sdk:^1.0

    {
        "require": {
            "buckaroo/buckaroo-sdk": "^1.0"
        }
    }

### Example
Create and config the Buckaroo object

```php
require __DIR__ . '/vendor/autoload.php';

# Get your website & secret key in your plaza.
$buckaroo = new \Buckaroo('WEBSITE_KEY', 'SECRET_KEY');
```

Create a payment with all the available payment methods. In this example, we show how to create a credit card payment. Each payment has a slightly different payload.

```php
# Create a new payment
$buckaroo->payment('creditcard') // Input the desire payment method.
    ->pay([
        'name'          => 'visa' // Request to pay with Visa
        'amountDebit'   => 10, // The amount we want to charge
        'invoice'       => 'UNIQUE-INVOICE-NO', // Each payment must contain a unique invoice number
    ]);
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
