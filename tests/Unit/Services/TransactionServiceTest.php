<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use Buckaroo\Transaction\Response\Response;
use Buckaroo\Transaction\Response\TransactionResponse;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    public function test_status_fetches_transaction_status_successfully(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                "*/Transaction/Status/{$transactionKey}",
                TestHelpers::successResponse([
                    'Key' => $transactionKey,
                    'Status' => [
                        'Code' => ['Code' => 190],
                        'SubCode' => ['Code' => 'S001'],
                    ],
                ])
            ),
        ]);

        $service = $this->buckaroo->transaction($transactionKey);
        $response = $service->status();

        $this->assertInstanceOf(TransactionResponse::class, $response);
        $this->assertTrue($response->isSuccess());
        $this->assertSame($transactionKey, $response->getTransactionKey());
    }

    public function test_status_parses_transaction_response_data(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $paymentKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                "*/Transaction/Status/{$transactionKey}",
                TestHelpers::successResponse([
                    'Key' => $transactionKey,
                    'PaymentKey' => $paymentKey,
                    'Invoice' => 'INV-99999',
                    'AmountDebit' => 25.50,
                    'Currency' => 'USD',
                    'Status' => [
                        'Code' => ['Code' => 190],
                        'SubCode' => ['Code' => 'S001'],
                    ],
                ])
            ),
        ]);

        $service = $this->buckaroo->transaction($transactionKey);
        $response = $service->status();

        $this->assertSame($transactionKey, $response->getTransactionKey());
        $this->assertSame($paymentKey, $response->getPaymentKey());
        $this->assertSame('INV-99999', $response->getInvoice());
        $this->assertSame('25.5', $response->getAmount());
        $this->assertSame('USD', $response->getCurrency());
        $this->assertSame(190, $response->getStatusCode());
    }

    public function test_refund_info_fetches_refund_information_successfully(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                "*/Transaction/RefundInfo/{$transactionKey}",
                TestHelpers::successResponse([
                    'Key' => $transactionKey,
                    'AmountCredit' => 10.00,
                    'Status' => [
                        'Code' => ['Code' => 190],
                    ],
                ])
            ),
        ]);

        $service = $this->buckaroo->transaction($transactionKey);
        $response = $service->refundInfo();

        $this->assertInstanceOf(Response::class, $response);
        $data = $response->toArray();
        $this->assertSame($transactionKey, $data['Key']);
    }

    public function test_refund_info_returns_response_object(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                "*/Transaction/RefundInfo/{$transactionKey}",
                TestHelpers::successResponse([
                    'Key' => $transactionKey,
                    'MaximumAmountToRefund' => 50.00,
                    'RemainingAmountToRefund' => 25.00,
                ])
            ),
        ]);

        $service = $this->buckaroo->transaction($transactionKey);
        $response = $service->refundInfo();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotInstanceOf(TransactionResponse::class, $response);

        $data = $response->toArray();
        $this->assertArrayHasKey('Key', $data);
        $this->assertSame($transactionKey, $data['Key']);
    }

    public function test_cancel_info_fetches_cancel_information_successfully(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                "*/Transaction/Cancel/{$transactionKey}",
                TestHelpers::successResponse([
                    'Key' => $transactionKey,
                    'Status' => [
                        'Code' => ['Code' => 190],
                    ],
                ])
            ),
        ]);

        $service = $this->buckaroo->transaction($transactionKey);
        $response = $service->cancelInfo();

        $this->assertInstanceOf(Response::class, $response);
        $data = $response->toArray();
        $this->assertSame($transactionKey, $data['Key']);
    }

    public function test_cancel_info_returns_response_object(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                "*/Transaction/Cancel/{$transactionKey}",
                TestHelpers::successResponse([
                    'Key' => $transactionKey,
                    'IsCancellable' => true,
                ])
            ),
        ]);

        $service = $this->buckaroo->transaction($transactionKey);
        $response = $service->cancelInfo();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotInstanceOf(TransactionResponse::class, $response);

        $data = $response->toArray();
        $this->assertArrayHasKey('Key', $data);
        $this->assertSame($transactionKey, $data['Key']);
    }

    public function test_handles_http_404_not_found_error(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                "*/Transaction/Status/{$transactionKey}",
                [
                    'Message' => 'Transaction not found',
                    'Status' => [
                        'Code' => ['Code' => 404],
                    ],
                ],
                404
            ),
        ]);

        $service = $this->buckaroo->transaction($transactionKey);
        $response = $service->status();

        $this->assertInstanceOf(TransactionResponse::class, $response);
        $this->assertFalse($response->isSuccess());
    }

    public function test_handles_http_401_unauthorized_error(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                "*/Transaction/RefundInfo/{$transactionKey}",
                [
                    'Message' => 'Unauthorized',
                    'Status' => [
                        'Code' => ['Code' => 401],
                    ],
                ],
                401
            ),
        ]);

        $service = $this->buckaroo->transaction($transactionKey);
        $response = $service->refundInfo();

        $this->assertInstanceOf(Response::class, $response);
        $data = $response->toArray();
        $this->assertArrayHasKey('Message', $data);
        $this->assertStringContainsString('Unauthorized', $data['Message']);
    }

    public function test_handles_http_500_server_error(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                "*/Transaction/Cancel/{$transactionKey}",
                [
                    'Message' => 'Internal server error',
                    'Status' => [
                        'Code' => ['Code' => 500],
                    ],
                ],
                500
            ),
        ]);

        $service = $this->buckaroo->transaction($transactionKey);
        $response = $service->cancelInfo();

        $this->assertInstanceOf(Response::class, $response);
        $data = $response->toArray();
        $this->assertArrayHasKey('Message', $data);
        $this->assertStringContainsString('Internal server error', $data['Message']);
    }
}
