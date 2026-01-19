<?php

declare(strict_types=1);

namespace Tests\Unit\Transaction\Request;

use Buckaroo\PaymentMethods\iDeal\iDeal;
use Buckaroo\Transaction\Request\BatchRequest;
use Buckaroo\Transaction\Request\Request;
use ReflectionClass;
use Tests\TestCase;

class BatchRequestTest extends TestCase
{
    public function test_creates_instance(): void
    {
        $batchRequest = new BatchRequest([]);

        $this->assertInstanceOf(BatchRequest::class, $batchRequest);
    }

    public function test_extends_request_class(): void
    {
        $batchRequest = new BatchRequest([]);

        $this->assertInstanceOf(Request::class, $batchRequest);
    }

    public function test_stores_empty_transactions(): void
    {
        $batchRequest = new BatchRequest([]);

        $this->assertSame([], $this->getTransactions($batchRequest));
    }

    public function test_stores_transactions(): void
    {
        $client = $this->buckaroo->client();
        $payment1 = $this->createIdealPayment($client, 'INV-001', 10.00);
        $payment2 = $this->createIdealPayment($client, 'INV-002', 20.00);

        $batchRequest = new BatchRequest([$payment1, $payment2]);
        $transactions = $this->getTransactions($batchRequest);

        $this->assertCount(2, $transactions);
        $this->assertSame($payment1, $transactions[0]);
        $this->assertSame($payment2, $transactions[1]);
    }

    public function test_preserves_transaction_order(): void
    {
        $client = $this->buckaroo->client();
        $payment1 = $this->createIdealPayment($client, 'FIRST', 10.00);
        $payment2 = $this->createIdealPayment($client, 'SECOND', 20.00);
        $payment3 = $this->createIdealPayment($client, 'THIRD', 30.00);

        $batchRequest = new BatchRequest([$payment1, $payment2, $payment3]);
        $transactions = $this->getTransactions($batchRequest);

        $this->assertSame($payment1, $transactions[0]);
        $this->assertSame($payment2, $transactions[1]);
        $this->assertSame($payment3, $transactions[2]);
    }

    public function test_to_json_returns_string(): void
    {
        $batchRequest = new BatchRequest([]);

        $this->assertIsString($batchRequest->toJson());
    }

    public function test_to_json_returns_valid_json(): void
    {
        $client = $this->buckaroo->client();
        $payment = $this->createIdealPayment($client, 'INV-001', 10.00);

        $batchRequest = new BatchRequest([$payment]);

        $decoded = json_decode($batchRequest->toJson(), true);

        $this->assertSame(JSON_ERROR_NONE, json_last_error());
        $this->assertIsArray($decoded);
    }

    public function test_to_json_empty_transactions(): void
    {
        $batchRequest = new BatchRequest([]);

        $this->assertSame('[]', $batchRequest->toJson());
    }

    public function test_to_json_single_transaction(): void
    {
        $client = $this->buckaroo->client();
        $payment = $this->createIdealPayment($client, 'SINGLE-001', 25.50);

        $batchRequest = new BatchRequest([$payment]);

        $decoded = json_decode($batchRequest->toJson(), true);

        $this->assertCount(1, $decoded);
        $this->assertArrayHasKey('Invoice', $decoded[0]);
        $this->assertSame('SINGLE-001', $decoded[0]['Invoice']);
    }

    public function test_to_json_multiple_transactions(): void
    {
        $client = $this->buckaroo->client();

        $batchRequest = new BatchRequest([
            $this->createIdealPayment($client, 'INV-001', 10.00),
            $this->createIdealPayment($client, 'INV-002', 20.00),
            $this->createIdealPayment($client, 'INV-003', 30.00),
        ]);

        $decoded = json_decode($batchRequest->toJson(), true);

        $this->assertCount(3, $decoded);
        $this->assertSame('INV-001', $decoded[0]['Invoice']);
        $this->assertSame('INV-002', $decoded[1]['Invoice']);
        $this->assertSame('INV-003', $decoded[2]['Invoice']);
    }

    public function test_to_json_includes_amount(): void
    {
        $client = $this->buckaroo->client();
        $payment = $this->createIdealPayment($client, 'INV-001', 99.99);

        $batchRequest = new BatchRequest([$payment]);

        $decoded = json_decode($batchRequest->toJson(), true);

        $this->assertSame(99.99, $decoded[0]['AmountDebit']);
    }

    public function test_to_json_includes_service_parameters(): void
    {
        $client = $this->buckaroo->client();
        $payment = $this->createIdealPayment($client, 'INV-001', 10.00, 'RABONL2U');

        $batchRequest = new BatchRequest([$payment]);

        $decoded = json_decode($batchRequest->toJson(), true);

        $this->assertArrayHasKey('Services', $decoded[0]);
    }

    private function createIdealPayment($client, string $invoice, float $amount, string $issuer = 'ABNANL2A'): iDeal
    {
        $payment = new iDeal($client, 'ideal');
        $payment->manually(true);
        $payment->setPayload([
            'amountDebit' => $amount,
            'invoice' => $invoice,
            'issuer' => $issuer,
        ]);
        $payment->pay();

        return $payment;
    }

    private function getTransactions(BatchRequest $batchRequest): array
    {
        $reflection = new ReflectionClass($batchRequest);
        $property = $reflection->getProperty('transactions');
        $property->setAccessible(true);

        return $property->getValue($batchRequest);
    }
}
