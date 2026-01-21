<?php

declare(strict_types=1);

namespace Tests\Unit\Transaction\Response;

use Buckaroo\Transaction\Response\TransactionResponse;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class TransactionResponseTest extends TestCase
{
    public function test_extends_response_class(): void
    {
        $response = new TransactionResponse(null, []);

        $this->assertInstanceOf(\Buckaroo\Transaction\Response\Response::class, $response);
    }

    public function test_returns_true_for_is_success_with_status_190(): void
    {
        $data = [
            'Status' => ['Code' => ['Code' => 190]],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isFailed());
    }

    public function test_returns_true_for_is_failed_with_status_490(): void
    {
        $data = [
            'Status' => ['Code' => ['Code' => 490]],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->isFailed());
        $this->assertFalse($response->isSuccess());
    }

    public function test_returns_true_for_is_canceled_by_user(): void
    {
        $data = [
            'Status' => ['Code' => ['Code' => 890]],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->isCanceled());
        $this->assertFalse($response->isSuccess());
    }

    public function test_returns_true_for_is_canceled_by_merchant(): void
    {
        $data = [
            'Status' => ['Code' => ['Code' => 891]],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->isCanceled());
        $this->assertFalse($response->isSuccess());
    }

    public function test_returns_true_for_is_awaiting_consumer(): void
    {
        $data = [
            'Status' => ['Code' => ['Code' => 792]],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->isAwaitingConsumer());
        $this->assertFalse($response->isSuccess());
    }

    public function test_returns_true_for_is_pending_processing(): void
    {
        $data = [
            'Status' => ['Code' => ['Code' => 791]],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->isPendingProcessing());
        $this->assertFalse($response->isSuccess());
    }

    public function test_returns_true_for_is_waiting_on_user_input(): void
    {
        $data = [
            'Status' => ['Code' => ['Code' => 790]],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->isWaitingOnUserInput());
        $this->assertFalse($response->isSuccess());
    }

    public function test_returns_true_for_is_rejected(): void
    {
        $data = [
            'Status' => ['Code' => ['Code' => 690]],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->isRejected());
        $this->assertFalse($response->isSuccess());
    }

    public function test_returns_true_for_is_pending_approval(): void
    {
        $data = [
            'Status' => ['Code' => ['Code' => 794]],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->isPendingApproval());
        $this->assertFalse($response->isSuccess());
    }

    public function test_returns_true_for_is_validation_failure(): void
    {
        $data = [
            'Status' => ['Code' => ['Code' => 491]],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->isValidationFailure());
        $this->assertFalse($response->isSuccess());
    }

    public function test_returns_status_code(): void
    {
        $data = [
            'Status' => ['Code' => ['Code' => 190]],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame(190, $response->getStatusCode());
    }

    public function test_returns_null_for_missing_status_code(): void
    {
        $response = new TransactionResponse(null, []);

        $this->assertNull($response->getStatusCode());
    }

    public function test_returns_sub_status_code(): void
    {
        $data = [
            'Status' => [
                'Code' => ['Code' => 190],
                'SubCode' => ['Code' => 'S001'],
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('S001', $response->getSubStatusCode());
    }

    public function test_returns_null_for_missing_sub_status_code(): void
    {
        $data = [
            'Status' => ['Code' => ['Code' => 190]],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertNull($response->getSubStatusCode());
    }

    public function test_returns_transaction_key(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $data = ['Key' => $transactionKey];

        $response = new TransactionResponse(null, $data);

        $this->assertSame($transactionKey, $response->getTransactionKey());
    }

    public function test_returns_payment_key(): void
    {
        $paymentKey = TestHelpers::generateTransactionKey();
        $data = ['PaymentKey' => $paymentKey];

        $response = new TransactionResponse(null, $data);

        $this->assertSame($paymentKey, $response->getPaymentKey());
    }

    public function test_returns_invoice(): void
    {
        $data = ['Invoice' => 'INV-12345'];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('INV-12345', $response->getInvoice());
    }

    public function test_returns_amount_as_string(): void
    {
        $data = ['AmountDebit' => 25.50];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('25.5', $response->getAmount());
    }

    public function test_returns_currency(): void
    {
        $data = ['Currency' => 'EUR'];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('EUR', $response->getCurrency());
    }

    public function test_returns_customer_name(): void
    {
        $data = ['CustomerName' => 'John Doe'];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('John Doe', $response->getCustomerName());
    }

    public function test_checks_for_redirect(): void
    {
        $data = [
            'RequiredAction' => [
                'Name' => 'Redirect',
                'RedirectURL' => 'https://payment.example.com/3ds',
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->hasRedirect());
    }

    public function test_returns_false_for_has_redirect_when_no_redirect(): void
    {
        $data = ['RequiredAction' => null];

        $response = new TransactionResponse(null, $data);

        $this->assertFalse($response->hasRedirect());
    }

    public function test_returns_false_for_has_redirect_when_action_is_not_redirect(): void
    {
        $data = [
            'RequiredAction' => [
                'Name' => 'Other',
                'RedirectURL' => 'https://example.com',
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertFalse($response->hasRedirect());
    }

    public function test_returns_redirect_url(): void
    {
        $data = [
            'RequiredAction' => [
                'Name' => 'Redirect',
                'RedirectURL' => 'https://payment.example.com/3ds',
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('https://payment.example.com/3ds', $response->getRedirectUrl());
    }

    public function test_returns_empty_string_when_no_redirect_url(): void
    {
        $response = new TransactionResponse(null, []);

        $this->assertSame('', $response->getRedirectUrl());
    }

    public function test_returns_payment_method(): void
    {
        $data = [
            'Services' => [
                ['Name' => 'ideal', 'Action' => 'Pay'],
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('ideal', $response->getMethod());
    }

    public function test_returns_service_action(): void
    {
        $data = [
            'Services' => [
                ['Name' => 'creditcard', 'Action' => 'Authorize'],
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('Authorize', $response->getServiceAction());
    }

    public function test_returns_service_parameters(): void
    {
        $data = [
            'Services' => [
                [
                    'Name' => 'ideal',
                    'Parameters' => [
                        ['Name' => 'Issuer', 'Value' => 'ABNANL2A'],
                        ['Name' => 'BIC', 'Value' => 'RABONL2U'],
                    ],
                ],
            ],
        ];

        $response = new TransactionResponse(null, $data);
        $params = $response->getServiceParameters();

        $this->assertIsArray($params);
        $this->assertSame('ABNANL2A', $params['issuer']);
        $this->assertSame('RABONL2U', $params['bic']);
    }

    public function test_converts_service_parameter_keys_to_lowercase(): void
    {
        $data = [
            'Services' => [
                [
                    'Parameters' => [
                        ['Name' => 'TransactionId', 'Value' => 'TX-123'],
                        ['Name' => 'UPPERCASE', 'Value' => 'test'],
                    ],
                ],
            ],
        ];

        $response = new TransactionResponse(null, $data);
        $params = $response->getServiceParameters();

        $this->assertArrayHasKey('transactionid', $params);
        $this->assertArrayHasKey('uppercase', $params);
        $this->assertSame('TX-123', $params['transactionid']);
    }

    public function test_returns_empty_array_when_no_service_parameters(): void
    {
        $data = [
            'Services' => [
                ['Name' => 'ideal'],
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame([], $response->getServiceParameters());
    }

    public function test_returns_custom_parameters(): void
    {
        $data = [
            'CustomParameters' => [
                'List' => [
                    ['Name' => 'OrderId', 'Value' => 'ORD-123'],
                    ['Name' => 'CustomField', 'Value' => 'CustomValue'],
                ],
            ],
        ];

        $response = new TransactionResponse(null, $data);
        $params = $response->getCustomParameters();

        $this->assertIsArray($params);
        $this->assertSame('ORD-123', $params['OrderId']);
        $this->assertSame('CustomValue', $params['CustomField']);
    }

    public function test_returns_empty_array_when_no_custom_parameters(): void
    {
        $response = new TransactionResponse(null, []);

        $this->assertSame([], $response->getCustomParameters());
    }

    public function test_returns_additional_parameters(): void
    {
        $data = [
            'AdditionalParameters' => [
                'AdditionalParameter' => [
                    ['Name' => 'token', 'Value' => 'abc123'],
                    ['Name' => 'signature', 'Value' => 'xyz789'],
                ],
            ],
        ];

        $response = new TransactionResponse(null, $data);
        $params = $response->getAdditionalParameters();

        $this->assertIsArray($params);
        $this->assertSame('abc123', $params['token']);
        $this->assertSame('xyz789', $params['signature']);
    }

    public function test_returns_empty_array_when_no_additional_parameters(): void
    {
        $response = new TransactionResponse(null, []);

        $this->assertSame([], $response->getAdditionalParameters());
    }

    public function test_returns_token_from_additional_parameters(): void
    {
        $data = [
            'AdditionalParameters' => [
                'AdditionalParameter' => [
                    ['Name' => 'token', 'Value' => '  my-token-123  '],
                ],
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('my-token-123', $response->getToken());
    }

    public function test_returns_signature_from_additional_parameters(): void
    {
        $data = [
            'AdditionalParameters' => [
                'AdditionalParameter' => [
                    ['Name' => 'signature', 'Value' => '  sig-abc-xyz  '],
                ],
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('sig-abc-xyz', $response->getSignature());
    }

    public function test_returns_data_with_specific_key(): void
    {
        $data = [
            'Key' => 'TX-123',
            'Invoice' => 'INV-456',
            'Currency' => 'EUR',
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('TX-123', $response->data('Key'));
        $this->assertSame('INV-456', $response->data('Invoice'));
        $this->assertSame('EUR', $response->data('Currency'));
    }

    public function test_returns_all_data_when_no_key_specified(): void
    {
        $data = [
            'Key' => 'TX-789',
            'Invoice' => 'INV-999',
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame($data, $response->data());
    }

    public function test_returns_null_for_non_existent_data_key(): void
    {
        $data = ['Key' => 'TX-000'];

        $response = new TransactionResponse(null, $data);

        $this->assertNull($response->data('NonExistent'));
    }

    public function test_returns_value_with_get_method(): void
    {
        $data = [
            'CustomField' => 'CustomValue',
            'AnotherField' => 'AnotherValue',
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('CustomValue', $response->get('CustomField'));
        $this->assertSame('AnotherValue', $response->get('AnotherField'));
    }

    public function test_returns_null_for_non_existent_get_key(): void
    {
        $response = new TransactionResponse(null, ['Key' => 'TX-111']);

        $this->assertNull($response->get('NonExistent'));
    }

    public function test_detects_request_errors(): void
    {
        $data = [
            'RequestErrors' => [
                'ServiceErrors' => [
                    ['ErrorMessage' => 'Invalid service configuration'],
                ],
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->hasError());
    }

    public function test_detects_channel_errors(): void
    {
        $data = [
            'RequestErrors' => [
                'ChannelErrors' => [
                    ['ErrorMessage' => 'Channel not available'],
                ],
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->hasError());
    }

    public function test_detects_parameter_errors(): void
    {
        $data = [
            'RequestErrors' => [
                'ParameterErrors' => [
                    ['ErrorMessage' => 'Invalid parameter value'],
                ],
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->hasError());
    }

    public function test_returns_false_for_has_error_when_no_errors(): void
    {
        $response = new TransactionResponse(null, []);

        $this->assertFalse($response->hasError());
    }

    public function test_returns_first_error(): void
    {
        $data = [
            'RequestErrors' => [
                'ServiceErrors' => [
                    ['ErrorMessage' => 'First service error'],
                    ['ErrorMessage' => 'Second service error'],
                ],
            ],
        ];

        $response = new TransactionResponse(null, $data);
        $error = $response->getFirstError();

        $this->assertIsArray($error);
        $this->assertSame('First service error', $error['ErrorMessage']);
    }

    public function test_returns_empty_array_when_no_errors(): void
    {
        $response = new TransactionResponse(null, []);

        $this->assertSame([], $response->getFirstError());
    }

    public function test_prioritizes_error_types_correctly(): void
    {
        $data = [
            'RequestErrors' => [
                'ActionErrors' => [
                    ['ErrorMessage' => 'Action error'],
                ],
                'ChannelErrors' => [
                    ['ErrorMessage' => 'Channel error'],
                ],
            ],
        ];

        $response = new TransactionResponse(null, $data);
        $error = $response->getFirstError();

        $this->assertSame('Channel error', $error['ErrorMessage']);
    }

    public function test_detects_has_message(): void
    {
        $data = ['Message' => 'Transaction completed'];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->hasMessage());
    }

    public function test_returns_message(): void
    {
        $data = ['Message' => 'Payment successful'];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('Payment successful', $response->getMessage());
    }

    public function test_returns_empty_string_when_no_message(): void
    {
        $response = new TransactionResponse(null, []);

        $this->assertSame('', $response->getMessage());
    }

    public function test_detects_has_consumer_message(): void
    {
        $data = [
            'ConsumerMessage' => [
                'HtmlText' => '<p>Payment pending</p>',
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->hasConsumerMessage());
    }

    public function test_returns_consumer_message(): void
    {
        $data = [
            'ConsumerMessage' => [
                'HtmlText' => '<p>Payment approved</p>',
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('<p>Payment approved</p>', $response->getConsumerMessage());
    }

    public function test_returns_empty_string_when_no_consumer_message(): void
    {
        $response = new TransactionResponse(null, []);

        $this->assertSame('', $response->getConsumerMessage());
    }

    public function test_detects_has_sub_code_message(): void
    {
        $data = [
            'Status' => [
                'SubCode' => [
                    'Code' => 'S001',
                    'Description' => 'Transaction successful',
                ],
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->hasSubCodeMessage());
    }

    public function test_returns_sub_code_message(): void
    {
        $data = [
            'Status' => [
                'SubCode' => [
                    'Description' => 'Awaiting confirmation',
                ],
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('Awaiting confirmation', $response->getSubCodeMessage());
    }

    public function test_returns_empty_string_when_no_sub_code_message(): void
    {
        $response = new TransactionResponse(null, []);

        $this->assertSame('', $response->getSubCodeMessage());
    }

    public function test_detects_has_some_error(): void
    {
        $data = [
            'RequestErrors' => [
                'ServiceErrors' => [
                    ['ErrorMessage' => 'Service unavailable'],
                ],
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertTrue($response->hasSomeError());
    }

    public function test_returns_some_error_from_request_errors(): void
    {
        $data = [
            'RequestErrors' => [
                'ServiceErrors' => [
                    ['ErrorMessage' => 'Service temporarily unavailable'],
                ],
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('Service temporarily unavailable', $response->getSomeError());
    }

    public function test_returns_some_error_from_consumer_message(): void
    {
        $data = [
            'ConsumerMessage' => [
                'HtmlText' => 'Payment failed',
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('Payment failed', $response->getSomeError());
    }

    public function test_returns_some_error_from_message(): void
    {
        $data = ['Message' => 'Transaction declined'];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('Transaction declined', $response->getSomeError());
    }

    public function test_returns_some_error_from_sub_code(): void
    {
        $data = [
            'Status' => [
                'SubCode' => [
                    'Description' => 'Insufficient funds',
                ],
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('Insufficient funds', $response->getSomeError());
    }

    public function test_prioritizes_errors_correctly_in_get_some_error(): void
    {
        $data = [
            'RequestErrors' => [
                'ServiceErrors' => [
                    ['ErrorMessage' => 'Service error'],
                ],
            ],
            'ConsumerMessage' => [
                'HtmlText' => 'Consumer message',
            ],
            'Message' => 'General message',
            'Status' => [
                'SubCode' => ['Description' => 'SubCode description'],
            ],
        ];

        $response = new TransactionResponse(null, $data);

        $this->assertSame('Service error', $response->getSomeError());
    }

    public function test_returns_empty_string_when_no_errors_at_all(): void
    {
        $response = new TransactionResponse(null, []);

        $this->assertSame('', $response->getSomeError());
        $this->assertFalse($response->hasSomeError());
    }
}
