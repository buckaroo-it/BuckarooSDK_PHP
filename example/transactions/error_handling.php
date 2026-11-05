<?php

require_once '../bootstrap.php';

use Buckaroo\BuckarooClient;
use Buckaroo\Exceptions\AuthenticationException;
use Buckaroo\Exceptions\BuckarooApiException;
use Buckaroo\Exceptions\BuckarooException;
use Buckaroo\Exceptions\ParameterValidationException;
use Buckaroo\Exceptions\TransferException;

$buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

try {
    $response = $buckaroo->method('ideal')->pay([
        'returnURL' => 'https://example.com/return',
        'invoice' => uniqid(),
        'amountDebit' => 10.10,
    ]);
} catch (ParameterValidationException $e) {
    // Invalid input: nothing was sent to Buckaroo. Fix the request.
    exit('Invalid input (' . $e->getParameterName() . '): ' . $e->getMessage() . PHP_EOL);
} catch (AuthenticationException $e) {
    // Missing or rejected credentials: check the keys and the mode (test or live)
    exit('Check the website key and secret key: ' . $e->getMessage() . PHP_EOL);
} catch (TransferException $e) {
    // No response from Buckaroo (connection failure, timeout). The payment may or may not
    // have been created: wait for the push before retrying, so the customer is not charged twice.
    exit('Buckaroo could not be reached: ' . $e->getMessage() . PHP_EOL);
} catch (BuckarooApiException $e) {
    // Buckaroo returned a response the SDK could not process
    exit('Buckaroo API error ' . $e->getHttpStatus() . ': ' . $e->getHttpBody() . PHP_EOL);
} catch (BuckarooException $e) {
    // Any other SDK error
    exit('Buckaroo SDK error: ' . $e->getMessage() . PHP_EOL);
}

// Buckaroo answered: a declined or rejected payment is a normal response, not an exception
if ($response->isSuccess()) {
    echo 'Paid: ' . $response->getTransactionKey() . PHP_EOL;
} elseif ($response->hasRedirect()) {
    echo 'Redirect the customer to ' . $response->getRedirectUrl() . PHP_EOL;
} else {
    echo 'Payment not successful: ' . $response->getStatusCode() . ' ' . $response->getSomeError() . PHP_EOL;
}
