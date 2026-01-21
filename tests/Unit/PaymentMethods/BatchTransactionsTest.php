<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods;

use Buckaroo\PaymentMethods\BatchTransactions;
use Buckaroo\PaymentMethods\iDeal\iDeal;
use Buckaroo\Transaction\Request\BatchRequest;
use ReflectionClass;
use Tests\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class BatchTransactionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    public function test_creates_instance_with_empty_transactions(): void
    {
        $batch = new BatchTransactions($this->buckaroo->client(), []);

        $this->assertInstanceOf(BatchTransactions::class, $batch);
    }

    public function test_creates_instance_with_transactions(): void
    {
        $client = $this->buckaroo->client();
        $payment = $this->createIdealPayment($client, 'TEST-001', 10.00);

        $batch = new BatchTransactions($client, [$payment]);

        $this->assertInstanceOf(BatchTransactions::class, $batch);
    }

    public function test_stores_client_reference(): void
    {
        $client = $this->buckaroo->client();
        $batch = new BatchTransactions($client, []);

        $storedClient = $this->getProperty($batch, 'client');

        $this->assertSame($client, $storedClient);
    }

    public function test_creates_batch_request_internally(): void
    {
        $batch = new BatchTransactions($this->buckaroo->client(), []);

        $batchRequest = $this->getProperty($batch, 'batch_transactions');

        $this->assertInstanceOf(BatchRequest::class, $batchRequest);
    }

    public function test_created_via_buckaroo_client(): void
    {
        $batch = $this->buckaroo->batch([]);

        $this->assertInstanceOf(BatchTransactions::class, $batch);
    }

    public function test_batch_request_contains_transactions(): void
    {
        $client = $this->buckaroo->client();
        $payment1 = $this->createIdealPayment($client, 'INV-001', 10.00);
        $payment2 = $this->createIdealPayment($client, 'INV-002', 20.00);

        $batch = new BatchTransactions($client, [$payment1, $payment2]);

        $batchRequest = $this->getProperty($batch, 'batch_transactions');
        $transactions = $this->getProperty($batchRequest, 'transactions');

        $this->assertCount(2, $transactions);
        $this->assertSame($payment1, $transactions[0]);
        $this->assertSame($payment2, $transactions[1]);
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

    private function getProperty(object $object, string $property): mixed
    {
        $reflection = new ReflectionClass($object);
        $prop = $reflection->getProperty($property);
        $prop->setAccessible(true);

        return $prop->getValue($object);
    }
}
