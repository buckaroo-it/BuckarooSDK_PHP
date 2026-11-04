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
  <a href="#end-to-end-example-ideal-checkout">End-to-end example</a> &middot;
  <a href="#common-tasks">Common tasks</a> &middot;
  <a href="#payment-methods">Payment methods</a> &middot;
  <a href="#testing">Testing</a> &middot;
  <a href="#support">Support</a> &middot;
  <a href="#contribute">Contribute</a>
</p>

---

## About

Buckaroo is a Dutch Payment Service Provider. More than 54,000 organisations rely on the Buckaroo platform to process their payments, subscriptions and unpaid invoices.

This is Buckaroo's official PHP SDK: a modern, open source library that connects a PHP application to the Buckaroo API. It handles authentication, request signing and response parsing, so you can start a payment in a few lines. You can use it to start payments, verify return and push requests, and refund or look up transactions for more than 40 payment methods.

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

You can find your Store key and Secret key under [API credentials in Buckaroo Plaza](https://plaza.buckaroo.nl/Configuration/Merchant/ApiKeys). The third argument is the mode: `test` while developing, `live` in production. It defaults to `test`. In test mode, no real money is moved.

```php
require __DIR__ . '/vendor/autoload.php';

$buckaroo = new \Buckaroo\BuckarooClient('STORE_KEY', 'SECRET_KEY', 'test');
```

> [!TIP]
> Keep your Secret key out of version control. Load both keys from environment variables instead — the repository ships an [`.env.example`](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/.env.example) to start from.

Use `confirmCredential()` to check that the keys are valid, for example in a health check or an admin "test connection" button:

```php
if (! $buckaroo->confirmCredential()) {
    throw new RuntimeException('Invalid Buckaroo credentials');
}
```

<details>
<summary><strong>Advanced configuration and environment variables</strong></summary>

<br>

To set defaults such as currency, culture or HTTP timeouts, pass a `Config` object instead of the keys:

```php
use Buckaroo\BuckarooClient;
use Buckaroo\Config\DefaultConfig;

$config = new DefaultConfig(
    'STORE_KEY',
    'SECRET_KEY',
    'test',                                   // mode: 'test' or 'live'
    'EUR',                                    // currency
    'https://example.com/buckaroo/return',    // returnURL
    'https://example.com/buckaroo/cancel',    // returnURLCancel
    'https://example.com/buckaroo/push',      // pushURL
    'My Shop',                                // platformName
    '1.0.0',                                  // platformVersion
    'My Company',                             // moduleSupplier
    'my-shop-buckaroo',                       // moduleName
    '1.0.0',                                  // moduleVersion
    'nl-NL',                                  // culture (language of the payment pages)
    'Web',                                    // channel
    null,                                     // logger (see "Logging")
    30,                                       // timeout in seconds
    10                                        // connect timeout in seconds
);

$buckaroo = new BuckarooClient($config);
```

If any of these variables are set in `$_ENV`, the SDK uses them. **They take precedence over the values passed to the constructor.**

| Variable | Purpose | Default |
|---|---|---|
| `BPE_MODE` | `test` or `live` | `test` |
| `BPE_CURRENCY_CODE` | Default currency | `EUR` |
| `BPE_RETURN_URL` | Default return URL | — |
| `BPE_RETURN_URL_CANCEL` | Default cancel URL | — |
| `BPE_PUSH_URL` | Default push URL | — |
| `BPE_HTTP_TIMEOUT` | Request timeout (seconds) | — |
| `BPE_HTTP_CONNECT_TIMEOUT` | Connect timeout (seconds) | — |

</details>

### How a payment works

Most online payment methods follow the same flow:

1. **Start the payment.** Your server calls `pay()`. Buckaroo responds with a transaction key and, for most methods, a redirect URL.
2. **Redirect the customer** to that URL so they can complete the payment with their bank or card provider.
3. **The customer comes back** to your `returnURL`. Use this to show a "thank you" or "payment failed" page.
4. **Buckaroo sends a push** (server-to-server notification) to your `pushURL`. **Use the push to update the order status in your database.** The customer may close the browser before step 3, but the push is always sent.

Always verify the signature of the return and push requests with the SDK before you trust their contents, as the [end-to-end example](#end-to-end-example-ideal-checkout) shows.

---

## End-to-end example: iDEAL checkout

This example is a complete, minimal integration. It has three files: one that starts the payment, one for the return page and one for the push endpoint. Copy the files, fill in your URLs, and you have a working test integration.

Each file assumes Composer's autoloader is loaded and `$buckaroo` is the client from [Configuring the client](#configuring-the-client).

**`checkout.php`**: starts the payment and redirects the customer

The SDK throws a `BuckarooException` for configuration problems, unknown payment methods or actions, and failed HTTP requests. A request that Buckaroo receives but rejects (for example a validation error) does **not** throw an exception, so also check the response:

```php
<?php

use Buckaroo\Exceptions\BuckarooException;

$orderId = 'ORDER-' . time(); // use your own order ID

try {
    $response = $buckaroo->method('ideal')->pay([
        'invoice'         => $orderId,
        'order'           => $orderId,
        'amountDebit'     => 24.95,
        'currency'        => 'EUR',
        'description'     => 'Order ' . $orderId,
        'returnURL'       => 'https://example.com/return.php',
        'returnURLCancel' => 'https://example.com/return.php',
        'pushURL'         => 'https://example.com/push.php',
    ]);
} catch (BuckarooException $e) {
    http_response_code(500);
    exit('Could not start the payment: ' . $e->getMessage());
}

// Store the transaction key with your order so you can match the push later
// saveTransactionKey($orderId, $response->getTransactionKey());

if ($response->hasRedirect()) {
    header('Location: ' . $response->getRedirectUrl());
    exit;
}

// No redirect: the request was rejected or failed validation
http_response_code(400);
echo 'Payment could not be started: ' . $response->getSomeError();
```

**`return.php`**: where the customer lands after paying

Buckaroo sends the customer back with a form `POST` that contains the transaction result (`brq_*` fields).

```php
<?php

use Buckaroo\Handlers\Reply\ReplyHandler;
use Buckaroo\Resources\Constants\ResponseStatus;

$reply = new ReplyHandler($buckaroo->client()->config(), $_POST);
$reply->validate();

if (! $reply->isValid()) {
    http_response_code(400);
    exit('Invalid signature');
}

switch ($reply->data('brq_statuscode')) {
    case ResponseStatus::BUCKAROO_STATUSCODE_SUCCESS:
        echo 'Thank you, your payment was received.';
        break;
    case ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_USER:
        echo 'You cancelled the payment.';
        break;
    case ResponseStatus::BUCKAROO_STATUSCODE_PENDING_PROCESSING:
    case ResponseStatus::BUCKAROO_STATUSCODE_WAITING_ON_CONSUMER:
        echo 'Your payment is being processed.';
        break;
    default:
        echo 'Your payment failed. Please try again.';
}
```

> [!IMPORTANT]
> Show the result to the customer here, but **don't update the order in your database here**. Do that in the push handler.

**`push.php`**: server-to-server notification from Buckaroo

This example handles the JSON push format. Buckaroo signs it with the `Authorization` header, which the SDK checks together with the request body and your push URL:

```php
<?php

use Buckaroo\Handlers\Reply\ReplyHandler;
use Buckaroo\Resources\Constants\ResponseStatus;

$body       = file_get_contents('php://input');
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$pushUrl    = 'https://example.com/push.php'; // must exactly match the pushURL you sent

$reply = new ReplyHandler($buckaroo->client()->config(), $body, $authHeader, $pushUrl);
$reply->validate();

if (! $reply->isValid()) {
    http_response_code(400);
    exit;
}

$transaction = json_decode($body, true)['Transaction'];

$invoice    = $transaction['Invoice'];
$txKey      = $transaction['Key'];
$statusCode = (string) $transaction['Status']['Code']['Code'];

if ($statusCode === ResponseStatus::BUCKAROO_STATUSCODE_SUCCESS) {
    // markOrderAsPaid($invoice, $txKey);
} elseif (in_array($statusCode, [
    ResponseStatus::BUCKAROO_STATUSCODE_FAILED,
    ResponseStatus::BUCKAROO_STATUSCODE_REJECTED,
    ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_USER,
    ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_MERCHANT,
], true)) {
    // markOrderAsFailed($invoice, $txKey);
}

http_response_code(200); // Buckaroo retries the push if it does not get a 200
```

> [!NOTE]
> If your website is set to the HTTP POST push format in Plaza, validate with `new ReplyHandler($buckaroo->client()->config(), $_POST)` instead, and read the fields with `$reply->data('brq_statuscode')`, `$reply->data('brq_invoicenumber')` and `$reply->data('brq_transactions')`.

---

## Common tasks

### Start a payment

Every payment method uses the same pattern: `$buckaroo->method('<service code>')-><action>([...])`. The fields below are shared by all methods. Each method can add its own fields (see [Payment methods](#payment-methods)).

| Field | Required | Description |
|---|---|---|
| `invoice` | yes | Your unique invoice number |
| `amountDebit` | yes | Amount to charge |
| `currency` | no | ISO currency code, defaults to the config value (`EUR`) |
| `order` | no | Your order number |
| `description` | no | Shown to the customer and in Plaza |
| `returnURL` | yes* | Where the customer is sent after paying |
| `returnURLCancel` / `returnURLError` / `returnURLReject` | no | Alternative return pages |
| `pushURL` | no | Where Buckaroo sends the push; falls back to the Plaza setting |
| `clientIP` | depends | Customer's IP address. Required by some methods, such as the Buy Now Pay Later methods |
| `customParameters` / `additionalParameters` | no | Your own key/value data. It is returned in the push |

\* Required for all methods that redirect the customer.

Credit card example:

```php
$response = $buckaroo->method('creditcard')->pay([
    'name'        => 'visa',      // card brand: visa, mastercard, amex, maestro, ...
    'invoice'     => 'INV-1002',
    'amountDebit' => 10.00,
    'returnURL'   => 'https://example.com/buckaroo/return',
]);
```

You can also pass the payload as a JSON string instead of an array.

### Read the response

`pay()`, `refund()`, `authorize()` and most other actions return a `TransactionResponse`:

```php
$response->isSuccess();            // status 190
$response->isPendingProcessing();  // status 791
$response->isAwaitingConsumer();   // status 792
$response->isWaitingOnUserInput(); // status 790
$response->isFailed();             // status 490
$response->isRejected();           // status 690
$response->isCanceled();           // status 890 / 891
$response->isValidationFailure();  // status 491

$response->hasRedirect();          // does the customer need to be redirected?
$response->getRedirectUrl();

$response->getTransactionKey();    // store this: you need it for refunds and lookups
$response->getStatusCode();
$response->getSomeError();         // human-readable error message, if any
$response->toArray();              // the full raw response
```

### Refund a payment

```php
$response = $buckaroo->method('ideal')->refund([
    'invoice'                => 'INV-1001',           // invoice of the original payment
    'originalTransactionKey' => 'ORIGINAL_TX_KEY',    // from $response->getTransactionKey()
    'amountCredit'           => 10.00,                // full or partial amount
]);

if ($response->isSuccess()) {
    // refund processed
}
```

Use the same payment method as the original payment (for example `creditcard` with the same card `name`).

### Retrieve transaction information

Once a transaction exists you can query it on demand:

```php
$transaction = $buckaroo->transaction('YOUR-TRANSACTION-KEY');

$transaction->status();     // transaction status (a TransactionResponse, e.g. ->isSuccess())
$transaction->refundInfo(); // refund info
$transaction->cancelInfo(); // cancellation info
```

### Authorize and capture

Some methods support reserving the amount first and capturing it later (for example credit cards, Bancontact, Riverty and In3). Klarna uses `reserve()` instead, followed by `pay()` to capture the reserved amount (see the [Klarna reserve example](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/klarnakp.php)).

```php
$auth = $buckaroo->method('creditcard')->authorize([
    'name'        => 'visa',
    'invoice'     => 'INV-1003',
    'amountDebit' => 50.00,
    'returnURL'   => 'https://example.com/buckaroo/return',
]);

// Later, e.g. when the order ships
$capture = $buckaroo->method('creditcard')->capture([
    'name'                   => 'visa',
    'invoice'                => 'INV-1003',
    'originalTransactionKey' => $auth->getTransactionKey(),
    'amountDebit'            => 50.00,
]);
```

### Logging

Attach a logger to see the requests and responses. The SDK includes a Monolog observer that writes to `stdout`:

```php
$buckaroo->attachLogger(new \Buckaroo\Handlers\Logging\Observers\Monolog());
```

To send the logs to your own logger, implement `Buckaroo\Handlers\Logging\Observer` and pass it to `attachLogger()`.

---

## Payment methods

Pass one of these service codes to `$buckaroo->method('...')`. Each method has its own payload fields. The [`example/`](https://github.com/buckaroo-it/BuckarooSDK_PHP/tree/master/example) folder has a runnable sample for each method, and the [API reference](https://docs.buckaroo.io/reference) describes all parameters.

| Method | Service code | Example |
|---|---|---|
| iDEAL | `ideal` | [ideal.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/ideal.php) |
| iDEAL QR | `ideal_qr` | [ideal_qr.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/ideal_qr.php) |
| Credit & debit cards | `creditcard` (or the brand: `visa`, `mastercard`, `amex`, …) | [creditcard.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/creditcard.php) |
| PayPal | `paypal` | [paypal.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/paypal.php) |
| Bancontact | `bancontact` | [bancontact.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/bancontact.php) |
| Apple Pay | `applepay` | [applepay.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/applepay.php) |
| Google Pay | `googlepay` | [googlepay.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/googlepay.php) |
| Click to Pay | `clicktopay` | [clicktopay.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/clicktopay.php) |
| PayByBank | `paybybank` | [payment_initiation.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/payment_initiation.php) |
| SEPA Direct Debit | `sepadirectdebit` | [sepa.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/sepa.php) |
| Bank transfer | `transfer` | [transfer.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/transfer.php) |
| Klarna | `klarna` / `klarnakp` | [klarna.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/klarna.php) / [klarnakp.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/klarnakp.php) |
| Riverty | `afterpay` | [afterpay.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/afterpay.php) |
| In3 | `in3` | [in3.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/in3.php) |
| Billink | `billink` | [billink.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/billink.php) |
| Gift cards | `giftcard` | [giftcards.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/giftcards.php) |
| EPS | `eps` | [eps.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/eps.php) |
| Przelewy24 | `przelewy24` | [przelewy24.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/przelewy24.php) |
| Blik | `blik` | [blik.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/blik.php) |
| Trustly | `trustly` | [trustly.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/trustly.php) |
| Twint | `twint` | [twint.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/twint.php) |
| Swish | `swish` | [swish.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/swish.php) |
| MB WAY | `mbway` | [mbway.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/mbway.php) |
| Multibanco | `multibanco` | [multibanco.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/multibanco.php) |
| Bizum | `bizum` | [bizum.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/bizum.php) |
| Wero | `wero` | [wero.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/wero.php) |
| Payconiq | `payconiq` | [payconiq.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/payconiq.php) |
| KBC | `kbc` | [kbc.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/kbc.php) |
| Belfius | `belfius` | [belfius.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/belfius.php) |
| Alipay | `alipay` | [alipay.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/alipay.php) |
| WeChat Pay | `wechatpay` | [wechat.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/wechat.php) |
| Point of Sale | `pospayment` | [pos.php](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/transactions/pos.php) |

**Additional services:** [Subscriptions](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/additional_services/subscriptions.php) · [Credit Management](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/additional_services/credit_management.php) · [Pay per Email](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/additional_services/pay_per_email.php) · [Marketplaces](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/additional_services/marketplaces.php) · [iDIN](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/additional_services/idin.php) · [Buckaroo Wallet](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/additional_services/wallets.php) · [Buckaroo Voucher](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/additional_services/voucher.php) · [SurePay](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/example/additional_services/surepay.php)

### Going live

1. Test your full flow in `test` mode, including the return page, the push handler and refunds.
2. Make sure your `pushURL` is publicly reachable over HTTPS and returns HTTP `200`.
3. Activate the payment methods you need for your website in Plaza.
4. Switch the mode to `live`:

```php
$buckaroo = new \Buckaroo\BuckarooClient('STORE_KEY', 'SECRET_KEY', 'live');
```

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
