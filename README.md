<p align="center">
  <img src="https://user-images.githubusercontent.com/7081446/178473472-c0c29ec5-762c-47de-9ed4-999e5ad6c70d.png" width="225px" position="center">
</p>

# Buckaroo SDK Plugin

<p align="center">
  <img src="https://user-images.githubusercontent.com/7081446/178476892-372b4f2b-f74d-4b00-b174-4fa1d5a468e5.png" position="center">
</p>

---
- [Requirements](#requirements)
- [Composer Installation](#composer-installation)
- [Requirements](#requirements)
- [Example](#example)
- [Versioning](#versioning)
---

## Requirements
To use the Buckaroo API client, the following things are required:

+ Get yourself a free [Buckaroo account](https://www.buckaroo.eu/solutions/request-form). No sign up costs.
+ Now you're ready to use the Buckaroo API client in test mode.
+ PHP >= 7.4
+ Up-to-date OpenSSL (or other SSL/TLS toolkit)

## Composer Installation

By far the easiest way to install the Buckaroo API client is to require it with [Composer](http://getcomposer.org/doc/00-intro.md).

    $ composer require buckaroo/buckaroo-sdk:^1.0

    {
        "require": {
            "buckaroo/buckaroo-sdk": "^1.0"
        }
    }

## Example
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

## Versioning
<p align="center">
  <img src="https://user-images.githubusercontent.com/7081446/178474134-f4c3976d-653c-4ca1-bcd1-48bf6d489196.png" width="600px" position="center">
</p>

- **MAJOR:** Breaking changes that require additional testing/caution
- **MINOR:** Changes that should not have a big impact
- **PATCHES:** Bug and hotfixes only

## Additional information
- **Knowledge base & FAQ:** [Dutch](https://www.buckaroo.nl/resources/integratie/woocommerce) or [English](https://www.buckaroo.eu/resources/integration/woocommerce)
- **Support:** https://support.buckaroo.eu/contact
- **Contact:** support@buckaroo.nl or +31 (0)30 711 50 50