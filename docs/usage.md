# Usage

This guide covers the payment fields, reading the response, refunds, transaction lookups, and authorize and capture.

## Start a payment

Every payment method uses the same pattern: `$buckaroo->method('<service code>')-><action>([...])`. The fields below are shared by all methods. Each method can add its own fields (see [Payment methods](payment-methods.md)).

> [!IMPORTANT]
> Write field names in **camelCase**, as in the table below (`amountCredit`, `originalTransactionKey`). The [API reference](https://docs.buckaroo.io/reference) uses PascalCase (`AmountCredit`, `OriginalTransactionKey`) because it describes the raw JSON request, which the SDK builds for you. The SDK **silently ignores** keys it doesn't recognise, including PascalCase ones, so a request with `'AmountCredit' => 4.00` is sent without an amount.

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

## Read the response

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

## Refund a payment

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

## Retrieve transaction information

Once a transaction exists you can query it on demand:

```php
$transaction = $buckaroo->transaction('YOUR-TRANSACTION-KEY');

$transaction->status();     // transaction status (a TransactionResponse, e.g. ->isSuccess())
$transaction->refundInfo(); // refund info
$transaction->cancelInfo(); // cancellation info
```

## Authorize and capture

Some methods support reserving the amount first and capturing it later (for example credit cards, Bancontact, Riverty and In3). Klarna uses `reserve()` instead, followed by `pay()` to capture the reserved amount (see the [Klarna reserve example](../example/transactions/klarnakp.php)).

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
