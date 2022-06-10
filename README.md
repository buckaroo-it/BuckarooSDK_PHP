<p align="center">
  <img src="https://www.buckaroo.eu/media/1372/buckaroo-news-banner2.png?anchor=center&mode=crop&width=800&height=600&rnd=131988553360000000" width="225px" position="center">
</p>

# Buckaroo SDK Plugin

<p align="center">
  <img src="https://user-images.githubusercontent.com/7081446/172777838-61a340f0-eb38-46f8-84b6-bd02235dc68f.png" position="center">
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
+ PHP >= 7.2
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
        'amountDebit' => 10, // The amount we want to charge
        'invoice' => 'UNIQUE-INVOICE-NO', // Each payment must contain a unique invoice number
        'serviceParameters' => [
            'name'          => 'visa' // Request to pay with Visa
        ]
    ]);
```

Find our full documentation online on [dev.buckaroo.nl](https://dev.buckaroo.nl/).

## Versioning
<p align="left">
  <img src="https://www.buckaroo.nl/media/3212/versioning.png" width="600px" position="center">
</p>

- **MAJOR:** Breaking changes that require additional testing/caution
- **MINOR:** Changes that should not have a big impact
- **PATCHES:** Bug and hotfixes only

## Additional information
- **Knowledge base & FAQ:** [Dutch](https://www.buckaroo.nl/resources/integratie/woocommerce) or [English](https://www.buckaroo.eu/resources/integration/woocommerce)
- **Support:** https://support.buckaroo.eu/contact
- **Contact:** support@buckaroo.nl or +31 (0)30 711 50 50