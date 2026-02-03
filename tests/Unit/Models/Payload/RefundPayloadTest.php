<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Payload;

use Buckaroo\Models\CustomParameters;
use Buckaroo\Models\Payload\RefundPayload;
use Tests\TestCase;

class RefundPayloadTest extends TestCase
{
    public function test_sets_amount_credit_from_payload(): void
    {
        $payload = new RefundPayload(['amountCredit' => 99.99]);

        $this->assertSame(99.99, $payload->amountCredit);
        $this->assertIsFloat($payload->amountCredit);
    }

    public function test_preserves_float_precision_for_amount_credit(): void
    {
        $payload = new RefundPayload(['amountCredit' => 123.456789]);

        $this->assertSame(123.456789, $payload->amountCredit);
        $this->assertIsFloat($payload->amountCredit);
    }

    public function test_handles_zero_and_negative_amounts(): void
    {
        $zeroPayload = new RefundPayload(['amountCredit' => 0.0]);
        $this->assertSame(0.0, $zeroPayload->amountCredit);
        $this->assertIsFloat($zeroPayload->amountCredit);

        $negativePayload = new RefundPayload(['amountCredit' => -50.00]);
        $this->assertSame(-50.00, $negativePayload->amountCredit);
        $this->assertIsFloat($negativePayload->amountCredit);
    }

    public function test_to_array_includes_amount_credit_and_inherited_properties(): void
    {
        $payload = new RefundPayload([
            'amountCredit' => 100.00,
            'currency' => 'EUR',
            'invoice' => 'INV-001',
            'originalTransactionKey' => 'TX-KEY-123',
            'description' => 'Refund for order',
        ]);

        $array = $payload->toArray();

        $this->assertArrayHasKey('amountCredit', $array);
        $this->assertSame(100.00, $array['amountCredit']);
        $this->assertArrayHasKey('currency', $array);
        $this->assertSame('EUR', $array['currency']);
        $this->assertArrayHasKey('invoice', $array);
        $this->assertSame('INV-001', $array['invoice']);
        $this->assertArrayHasKey('originalTransactionKey', $array);
        $this->assertSame('TX-KEY-123', $array['originalTransactionKey']);
        $this->assertArrayHasKey('description', $array);
        $this->assertSame('Refund for order', $array['description']);
    }

    public function test_combines_refund_and_payload_properties(): void
    {
        $payload = new RefundPayload([
            'amountCredit' => 75.50,
            'currency' => 'USD',
            'invoice' => 'INV-REFUND-001',
            'originalTransactionKey' => 'ORIGINAL-TX-456',
            'returnURL' => 'https://example.com/refund-return',
            'pushURL' => 'https://example.com/refund-push',
            'description' => 'Customer refund request',
            'customParameters' => ['reason' => 'damaged', 'userId' => '67890'],
        ]);

        $this->assertSame(75.50, $payload->amountCredit);
        $this->assertSame('USD', $payload->currency);
        $this->assertSame('INV-REFUND-001', $payload->invoice);
        $this->assertSame('ORIGINAL-TX-456', $payload->originalTransactionKey);
        $this->assertSame('https://example.com/refund-return', $payload->returnURL);
        $this->assertSame('https://example.com/refund-push', $payload->pushURL);
        $this->assertSame('Customer refund request', $payload->description);
        $this->assertInstanceOf(CustomParameters::class, $payload->customParameters);

        $array = $payload->toArray();
        $this->assertCount(8, $array);
        $this->assertArrayHasKey('amountCredit', $array);
        $this->assertArrayHasKey('customParameters', $array);
    }
}
