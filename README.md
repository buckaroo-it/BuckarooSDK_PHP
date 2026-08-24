<p align="center">
  <a href="https://www.buckaroo.nl">
    <img src="https://raw.githubusercontent.com/buckaroo-it/Media/main/Buckaroo/README.md%20Headers/buckaroo-php-sdk-header-rounded.png" alt="Buckaroo — PHP SDK" width="100%">
  </a>
</p>

<h1 align="center">Buckaroo PHP SDK</h1>

<p align="center">
  <a href="https://packagist.org/packages/buckaroo/sdk"><img src="https://img.shields.io/packagist/v/buckaroo/sdk.svg?label=release" alt="Latest release"></a>
  <a href="https://packagist.org/packages/buckaroo/sdk"><img src="https://img.shields.io/packagist/php-v/buckaroo/sdk.svg?label=PHP" alt="PHP version"></a>
  <a href="https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/LICENSE"><img src="https://img.shields.io/packagist/l/buckaroo/sdk.svg?label=license" alt="License"></a>
  <a href="https://docs.buckaroo.io/docs/php-sdk"><img src="https://img.shields.io/badge/docs-docs.buckaroo.io-1a1a4b.svg" alt="Documentation"></a>
</p>

<p align="center">
  <a href="#about">About</a> &middot;
  <a href="#requirements">Requirements</a> &middot;
  <a href="#installation">Installation</a> &middot;
  <a href="#getting-started">Getting started</a> &middot;
  <a href="#testing">Testing</a> &middot;
  <a href="#support">Support</a> &middot;
  <a href="#contribute">Contribute</a>
</p>

---

## About

Buckaroo is a Dutch Payment Service Provider. More than 54,000 organisations rely on the Buckaroo platform to process their payments, subscriptions and unpaid invoices.

This is Buckaroo's official PHP SDK: a modern, open source library that connects a PHP application to the Buckaroo API. It handles authentication, request signing and response parsing, so you can start a payment in a few lines.

If you run a shop on an e-commerce platform, use the ready-made plugin for [Magento 2](https://github.com/buckaroo-it/Magento2), [Shopware 6](https://github.com/buckaroo-it/Shopware6), [WooCommerce](https://github.com/buckaroo-it/WooCommerce), [PrestaShop](https://github.com/buckaroo-it/PrestaShop) or [Odoo](https://github.com/buckaroo-it/Odoo) instead. For Laravel applications there is a dedicated [Laravel wrapper](https://github.com/buckaroo-it/BuckarooWrapper_Laravel) built on this SDK.

[Full API documentation on docs.buckaroo.io](https://docs.buckaroo.io/reference)

---

## Requirements

| Requirement | Supported versions |
|---|---|
| PHP | 7.4 or higher |
| PHP extensions | `json`, `pcre`, `fileinfo` |
| OpenSSL | An up-to-date SSL/TLS toolkit |

You also need a Buckaroo account. Don't have one yet? [Request an account](https://www.buckaroo.nl/start).

---

## Installation

Install the SDK with [Composer](https://getcomposer.org/doc/00-intro.md):

```bash
composer require buckaroo/sdk
```

Composer pulls in the SDK's own dependencies (Guzzle, Monolog, ramsey/uuid and composer/ca-bundle), so there is nothing else to set up.

---

## Getting started

### Configuring the client

You can find your Store key and Secret key under [API credentials in Buckaroo Plaza](https://plaza.buckaroo.nl/Configuration/Merchant/ApiKeys). The third argument is the mode: `test` while developing, `live` in production. It defaults to `test`.

```php
require __DIR__ . '/vendor/autoload.php';

$buckaroo = new \BuckarooClient('STORE_KEY', 'SECRET_KEY', 'test');
```

> [!TIP]
> Keep your Secret key out of version control. Load both keys from environment variables instead — the repository ships an [`.env.example`](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/.env.example) to start from.

### Creating a payment

Every payment method takes a slightly different payload. This example charges a Visa card:

```php
$response = $buckaroo->method('creditcard')
    ->pay([
        'name'        => 'visa',              // request to pay with Visa
        'amountDebit' => 10,                  // the amount to charge
        'invoice'     => 'UNIQUE-INVOICE-NO', // must be unique per payment
    ]);
```

Swap `creditcard` for any other service code to use a different payment method. Service codes and their parameters are listed in the [API reference](https://docs.buckaroo.io/reference).

### Retrieving transaction information

Once a transaction exists you can query it on demand:

```php
$transaction = $buckaroo->transaction('YOUR-TRANSACTION-KEY');

$transaction->status();     // transaction status
$transaction->refundInfo(); // refund info
$transaction->cancelInfo(); // cancellation info
```

More runnable examples are in [`example/`](https://github.com/buckaroo-it/BuckarooSDK_PHP/tree/master/example).

---

## Testing

The repository defines Composer scripts for the test suites and code style:

```bash
composer test          # run the full suite
composer test:unit     # unit tests only
composer test:feature  # feature tests only
composer cs            # check code style
composer cs:fix        # fix code style
```

Feature tests talk to the Buckaroo test environment, so copy `.env.example` to `.env` and fill in your test credentials first.

---

## Support

Having trouble? Work through this list before reaching out:

1. Check the [API reference](https://docs.buckaroo.io/reference) for the service and action you are calling.
2. Confirm you are on the [latest release](https://github.com/buckaroo-it/BuckarooSDK_PHP/releases).
3. Reproduce the issue in `test` mode and inspect the response, including the status subcode and message.
4. Verify that your push URL is reachable from outside your network. Buckaroo sends push messages from fixed IP addresses and ports, so make sure these are on your allow list. See [push messages](https://docs.buckaroo.io/docs/integration-push-messages) for the current list.

Still stuck? Contact us and include your PHP version, SDK version, the service and action you called, the error message and the transaction key.

- **Questions:** [start a discussion](https://github.com/buckaroo-it/BuckarooSDK_PHP/discussions)
- **Bug reports and feature requests:** [open an issue](https://github.com/buckaroo-it/BuckarooSDK_PHP/issues)
- **Technical support:** [support@buckaroo.nl](mailto:support@buckaroo.nl)
- **Phone:** +31 (0)30 711 50 50
- **Gateway status:** [status.buckaroo.io](https://status.buckaroo.io/)

---

## Contribute

We really appreciate it when developers help improve the Buckaroo SDKs. Please read our [Contribution Guidelines](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/CONTRIBUTING.md) before opening a pull request, and target the `master` branch.

Found a security issue? Please report it privately to [support@buckaroo.nl](mailto:support@buckaroo.nl) instead of opening a public issue.

---

## Versioning

We follow semantic versioning (`MAJOR.MINOR.PATCH`):

- **MAJOR** — breaking changes that require additional testing and caution.
- **MINOR** — new functionality with limited impact.
- **PATCH** — bug fixes and hotfixes only.

All changes are documented in the [changelog](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/CHANGELOG.md) and on the [releases page](https://github.com/buckaroo-it/BuckarooSDK_PHP/releases).

---

## License

This SDK is open source software licensed under the [MIT license](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/LICENSE).

---

<p align="center">
  <sub>Made with care by <a href="https://www.buckaroo.nl">Buckaroo</a>.<br>
  This document is subject to change; typos and language errors are possible.</sub>
</p>
