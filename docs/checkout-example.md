# End-to-end example: iDEAL checkout

This guide explains how a payment works and walks through a complete, minimal iDEAL integration.

## How a payment works

Most online payment methods follow the same flow:

1. **Start the payment.** Your server calls `pay()`. Buckaroo responds with a transaction key and, for most methods, a redirect URL.
2. **Redirect the customer** to that URL so they can complete the payment with their bank or card provider.
3. **The customer comes back** to your `returnURL`. Use this to show a "thank you" or "payment failed" page.
4. **Buckaroo sends a push** (server-to-server notification) to your `pushURL`. **Use the push to update the order status in your database.** The customer may close the browser before step 3, but the push is always sent.

Always verify the signature of the return and push requests with the SDK before you trust their contents, as the example below shows.

## The example

The example has three files: one that starts the payment, one for the return page and one for the push endpoint. Copy the files, fill in your URLs, and you have a working test integration.

Each file assumes Composer's autoloader is loaded and `$buckaroo` is the client from [Configuration](configuration.md#creating-the-client).

### `checkout.php`: starts the payment and redirects the customer

The SDK throws a `BuckarooException` when the request does not complete, for example because of invalid input, rejected keys or a connection failure. A payment that Buckaroo receives but declines does **not** throw an exception, so also check the response. See [Handle errors](usage.md#handle-errors) to handle each cause separately.

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

### `return.php`: where the customer lands after paying

Buckaroo sends the customer back with a form `POST` that contains the transaction result (`brq_*` fields).

```php
<?php

use Buckaroo\Exceptions\ReplyValidationException;
use Buckaroo\Handlers\Reply\ReplyHandler;
use Buckaroo\Resources\Constants\ResponseStatus;

$reply = new ReplyHandler($buckaroo->client()->config(), $_POST);

try {
    $reply->validate();
} catch (ReplyValidationException $e) {
    // Not a Buckaroo return, for example the customer refreshed the page
    http_response_code(400);
    exit('No payment result found.');
}

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

### `push.php`: server-to-server notification from Buckaroo

This example handles the JSON push format. Buckaroo signs it with the `Authorization` header, which the SDK checks together with the request body and your push URL:

```php
<?php

use Buckaroo\Exceptions\ReplyValidationException;
use Buckaroo\Handlers\Reply\ReplyHandler;
use Buckaroo\Resources\Constants\ResponseStatus;

$body       = file_get_contents('php://input');
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$pushUrl    = 'https://example.com/push.php'; // must exactly match the pushURL you sent

$reply = new ReplyHandler($buckaroo->client()->config(), $body, $authHeader, $pushUrl);

try {
    $reply->validate();
} catch (ReplyValidationException $e) {
    // Not a Buckaroo push, for example an empty request
    http_response_code(400);
    exit;
}

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

> [!IMPORTANT]
> Buckaroo can send the same push more than once. Make your push handler **idempotent**: processing the same push twice must have the same result as processing it once.
