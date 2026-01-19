<?php

declare(strict_types=1);

namespace Tests\Feature\PaymentMethods;

use Buckaroo\PaymentMethods\BatchTransactions;
use Buckaroo\PaymentMethods\iDeal\iDeal;
use Buckaroo\Transaction\Response\TransactionResponse;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class BatchTransactionsTest extends TestCase
{
    public function test_batch_created_via_client(): void
    {
        $batch = $this->buckaroo->batch([]);

        $this->assertInstanceOf(BatchTransactions::class, $batch);
    }

    public function test_batch_accepts_payment_methods(): void
    {
        $client = $this->buckaroo->client();

        $payment = new iDeal($client, 'ideal');
        $payment->manually(true);
        $payment->setPayload([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-001',
            'issuer' => 'ABNANL2A',
        ]);
        $payment->pay();

        $batch = $this->buckaroo->batch([$payment]);

        $this->assertInstanceOf(BatchTransactions::class, $batch);
    }

    public function test_execute_sends_to_batch_endpoint(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/batch/DataRequests', []),
        ]);

        $this->buckaroo->batch([])->execute();

        $this->assertTrue(true);
    }

    public function test_execute_returns_response(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/batch/DataRequests', [
                $this->successBatchItem('BATCH-001'),
            ]),
        ]);

        $response = $this->executeBatchWithPayment('BATCH-001', 25.00);

        $this->assertInstanceOf(TransactionResponse::class, $response);
    }

    public function test_execute_with_multiple_payments(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/batch/DataRequests', [
                $this->successBatchItem('MULTI-001'),
                $this->successBatchItem('MULTI-002'),
            ]),
        ]);

        $client = $this->buckaroo->client();

        $payment1 = $this->createIdealPayment($client, 'MULTI-001', 10.00);
        $payment2 = $this->createIdealPayment($client, 'MULTI-002', 20.00);

        $response = (new BatchTransactions($client, [$payment1, $payment2]))->execute();

        $this->assertInstanceOf(TransactionResponse::class, $response);
    }

    public function test_execute_with_three_payments(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/batch/DataRequests', [
                $this->successBatchItem('A'),
                $this->successBatchItem('B'),
                $this->successBatchItem('C'),
            ]),
        ]);

        $client = $this->buckaroo->client();

        $batch = new BatchTransactions($client, [
            $this->createIdealPayment($client, 'A', 10.00),
            $this->createIdealPayment($client, 'B', 20.00),
            $this->createIdealPayment($client, 'C', 30.00),
        ]);

        $this->assertInstanceOf(TransactionResponse::class, $batch->execute());
    }

    public function test_handles_failed_transaction(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/batch/DataRequests', [
                $this->failedBatchItem('FAILED-001'),
            ]),
        ]);

        $response = $this->executeBatchWithPayment('FAILED-001', 10.00);

        $this->assertInstanceOf(TransactionResponse::class, $response);
    }

    public function test_handles_partial_failure(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/batch/DataRequests', [
                $this->successBatchItem('SUCCESS'),
                $this->failedBatchItem('FAIL'),
            ]),
        ]);

        $client = $this->buckaroo->client();

        $batch = new BatchTransactions($client, [
            $this->createIdealPayment($client, 'SUCCESS', 10.00),
            $this->createIdealPayment($client, 'FAIL', 20.00),
        ]);

        $this->assertInstanceOf(TransactionResponse::class, $batch->execute());
    }

    public function test_handles_all_failed(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/batch/DataRequests', [
                $this->failedBatchItem('FAIL-1'),
                $this->failedBatchItem('FAIL-2'),
            ]),
        ]);

        $client = $this->buckaroo->client();

        $batch = new BatchTransactions($client, [
            $this->createIdealPayment($client, 'FAIL-1', 10.00),
            $this->createIdealPayment($client, 'FAIL-2', 20.00),
        ]);

        $this->assertInstanceOf(TransactionResponse::class, $batch->execute());
    }

    public function test_handles_pending_transaction(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/batch/DataRequests', [
                $this->pendingBatchItem('PENDING-001'),
            ]),
        ]);

        $response = $this->executeBatchWithPayment('PENDING-001', 10.00);

        $this->assertInstanceOf(TransactionResponse::class, $response);
    }

    public function test_execute_empty_batch(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/batch/DataRequests', []),
        ]);

        $response = $this->buckaroo->batch([])->execute();

        $this->assertInstanceOf(TransactionResponse::class, $response);
    }

    private function createIdealPayment($client, string $invoice, float $amount): iDeal
    {
        $payment = new iDeal($client, 'ideal');
        $payment->manually(true);
        $payment->setPayload([
            'amountDebit' => $amount,
            'invoice' => $invoice,
            'issuer' => 'ABNANL2A',
        ]);
        $payment->pay();

        return $payment;
    }

    private function executeBatchWithPayment(string $invoice, float $amount): TransactionResponse
    {
        $client = $this->buckaroo->client();
        $payment = $this->createIdealPayment($client, $invoice, $amount);

        return (new BatchTransactions($client, [$payment]))->execute();
    }

    private function successBatchItem(string $invoice): array
    {
        return [
            'Key' => TestHelpers::generateTransactionKey(),
            'Status' => [
                'Code' => ['Code' => 190, 'Description' => 'Success'],
                'SubCode' => ['Code' => 'S001'],
                'DateTime' => date('Y-m-d\TH:i:s'),
            ],
            'Invoice' => $invoice,
        ];
    }

    private function failedBatchItem(string $invoice): array
    {
        return [
            'Key' => TestHelpers::generateTransactionKey(),
            'Status' => [
                'Code' => ['Code' => 490, 'Description' => 'Failed'],
                'SubCode' => ['Code' => 'S991'],
                'DateTime' => date('Y-m-d\TH:i:s'),
            ],
            'Invoice' => $invoice,
        ];
    }

    private function pendingBatchItem(string $invoice): array
    {
        return [
            'Key' => TestHelpers::generateTransactionKey(),
            'Status' => [
                'Code' => ['Code' => 792, 'Description' => 'Pending'],
                'SubCode' => ['Code' => 'S001'],
                'DateTime' => date('Y-m-d\TH:i:s'),
            ],
            'Invoice' => $invoice,
        ];
    }
}
