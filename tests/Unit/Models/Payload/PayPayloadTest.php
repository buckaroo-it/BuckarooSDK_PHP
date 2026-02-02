<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Payload;

use Buckaroo\Models\CustomParameters;
use Buckaroo\Models\Payload\PayPayload;
use Tests\TestCase;

class PayPayloadTest extends TestCase
{
    public function test_auto_generates_order_with_correct_prefix(): void
    {
        $payload = new PayPayload(['amountDebit' => 10.50]);

        $this->assertStringStartsWith('ORDER_NO_', $payload->order);
    }

    public function test_order_uniqueness_across_instances(): void
    {
        $payload1 = new PayPayload(['amountDebit' => 10.00]);
        $payload2 = new PayPayload(['amountDebit' => 20.00]);
        $payload3 = new PayPayload(['amountDebit' => 30.00]);

        $this->assertNotSame($payload1->order, $payload2->order);
        $this->assertNotSame($payload2->order, $payload3->order);
        $this->assertNotSame($payload1->order, $payload3->order);
    }

    public function test_order_generated_with_null_payload(): void
    {
        $payload = new PayPayload(null);

        $this->assertNotNull($payload->order);
        $this->assertStringStartsWith('ORDER_NO_', $payload->order);
    }

    public function test_order_generated_with_empty_payload(): void
    {
        $payload = new PayPayload([]);

        $this->assertNotNull($payload->order);
        $this->assertStringStartsWith('ORDER_NO_', $payload->order);
    }

    public function test_sets_amount_debit_from_payload(): void
    {
        $payload = new PayPayload(['amountDebit' => 99.99]);

        $this->assertSame(99.99, $payload->amountDebit);
    }

    public function test_preserves_float_type_for_amount_debit(): void
    {
        $payload = new PayPayload(['amountDebit' => 150.75]);

        $this->assertIsFloat($payload->amountDebit);
        $this->assertSame(150.75, $payload->amountDebit);
    }

    public function test_preserves_float_precision(): void
    {
        $payload = new PayPayload(['amountDebit' => 123.456]);

        $this->assertSame(123.456, $payload->amountDebit);
    }

    public function test_handles_zero_amount_debit(): void
    {
        $payload = new PayPayload(['amountDebit' => 0.0]);

        $this->assertSame(0.0, $payload->amountDebit);
        $this->assertIsFloat($payload->amountDebit);
    }

    public function test_to_array_includes_order_and_amount_debit(): void
    {
        $payload = new PayPayload(['amountDebit' => 75.50]);

        $array = $payload->toArray();

        $this->assertArrayHasKey('order', $array);
        $this->assertArrayHasKey('amountDebit', $array);
        $this->assertStringStartsWith('ORDER_NO_', $array['order']);
        $this->assertSame(75.50, $array['amountDebit']);
    }

    public function test_to_array_includes_inherited_payload_properties(): void
    {
        $payload = new PayPayload([
            'amountDebit' => 100.00,
            'currency' => 'EUR',
            'invoice' => 'INV-001',
            'description' => 'Test payment',
        ]);

        $array = $payload->toArray();

        $this->assertArrayHasKey('order', $array);
        $this->assertArrayHasKey('amountDebit', $array);
        $this->assertArrayHasKey('currency', $array);
        $this->assertArrayHasKey('invoice', $array);
        $this->assertArrayHasKey('description', $array);
        $this->assertSame('EUR', $array['currency']);
        $this->assertSame('INV-001', $array['invoice']);
        $this->assertSame('Test payment', $array['description']);
    }

    public function test_to_array_with_nested_objects(): void
    {
        $payload = new PayPayload([
            'amountDebit' => 200.00,
            'currency' => 'USD',
            'customParameters' => ['ref' => 'REF-999'],
            'additionalParameters' => ['meta' => 'data'],
            'clientIP' => ['address' => '10.0.0.1', 'type' => 0],
        ]);

        $array = $payload->toArray();

        $this->assertArrayHasKey('order', $array);
        $this->assertArrayHasKey('amountDebit', $array);
        $this->assertArrayHasKey('currency', $array);
        $this->assertArrayHasKey('customParameters', $array);
        $this->assertArrayHasKey('additionalParameters', $array);
        $this->assertArrayHasKey('clientIP', $array);

        $this->assertIsArray($array['customParameters']);
        $this->assertIsArray($array['additionalParameters']);
        $this->assertIsArray($array['clientIP']);

        $this->assertSame(200.00, $array['amountDebit']);
        $this->assertSame('USD', $array['currency']);
    }

    public function test_magic_getter_accesses_order_and_amount_debit(): void
    {
        $payload = new PayPayload(['amountDebit' => 55.00]);

        $order = $payload->order;
        $amount = $payload->amountDebit;

        $this->assertStringStartsWith('ORDER_NO_', $order);
        $this->assertSame(55.00, $amount);
    }

    public function test_combines_pay_payload_and_payload_properties(): void
    {
        $payload = new PayPayload([
            'amountDebit' => 125.99,
            'currency' => 'GBP',
            'invoice' => 'INV-COMBINED',
            'returnURL' => 'https://example.com/return',
            'pushURL' => 'https://example.com/push',
            'description' => 'Combined properties test',
            'customParameters' => ['source' => 'web', 'userId' => '12345'],
        ]);

        $this->assertStringStartsWith('ORDER_NO_', $payload->order);
        $this->assertSame(125.99, $payload->amountDebit);
        $this->assertSame('GBP', $payload->currency);
        $this->assertSame('INV-COMBINED', $payload->invoice);
        $this->assertSame('https://example.com/return', $payload->returnURL);
        $this->assertSame('https://example.com/push', $payload->pushURL);
        $this->assertSame('Combined properties test', $payload->description);
        $this->assertInstanceOf(CustomParameters::class, $payload->customParameters);

        $array = $payload->toArray();
        $this->assertArrayHasKey('order', $array);
        $this->assertArrayHasKey('amountDebit', $array);
        $this->assertArrayHasKey('currency', $array);
        $this->assertArrayHasKey('invoice', $array);
        $this->assertArrayHasKey('returnURL', $array);
        $this->assertArrayHasKey('pushURL', $array);
        $this->assertArrayHasKey('description', $array);
        $this->assertArrayHasKey('customParameters', $array);
    }

    public function test_order_can_be_overridden_via_payload(): void
    {
        $payload = new PayPayload([
            'order' => 'CUSTOM-ORDER-123',
            'amountDebit' => 10.00,
        ]);

        $this->assertSame('CUSTOM-ORDER-123', $payload->order);
    }

    public function test_set_properties_can_override_auto_generated_order(): void
    {
        $payload = new PayPayload(['amountDebit' => 10.00]);

        $this->assertStringStartsWith('ORDER_NO_', $payload->order);

        $payload->setProperties([
            'order' => 'OVERRIDDEN-ORDER',
            'currency' => 'EUR',
        ]);

        $this->assertSame('OVERRIDDEN-ORDER', $payload->order);
        $this->assertSame('EUR', $payload->currency);
    }

    public function test_to_array_with_only_required_properties(): void
    {
        $payload = new PayPayload(['amountDebit' => 99.99]);

        $array = $payload->toArray();

        $this->assertArrayHasKey('order', $array);
        $this->assertArrayHasKey('amountDebit', $array);
    }

    public function test_handles_large_amount_values(): void
    {
        $payload = new PayPayload(['amountDebit' => 999999.99]);

        $this->assertSame(999999.99, $payload->amountDebit);
        $this->assertIsFloat($payload->amountDebit);
    }
}
