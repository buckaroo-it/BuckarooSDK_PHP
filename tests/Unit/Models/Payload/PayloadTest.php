<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Payload;

use Buckaroo\Models\AdditionalParameters;
use Buckaroo\Models\ClientIP;
use Buckaroo\Models\CustomParameters;
use Tests\TestCase;

class PayloadTest extends TestCase
{
    protected function makePayload(array $data = []): \Buckaroo\Models\Payload\Payload
    {
        return new class($data) extends \Buckaroo\Models\Payload\Payload {
        };
    }

    public function test_creates_nested_objects_from_constructor_data(): void
    {
        $payload = $this->makePayload([
            'customParameters' => ['key1' => 'value1'],
            'additionalParameters' => ['key2' => 'value2'],
            'clientIP' => ['address' => '192.168.1.1', 'type' => 0],
        ]);

        $this->assertInstanceOf(CustomParameters::class, $payload->customParameters);
        $this->assertInstanceOf(AdditionalParameters::class, $payload->additionalParameters);
        $this->assertInstanceOf(ClientIP::class, $payload->clientIP);
    }

    public function test_creates_custom_parameters_object_only(): void
    {
        $payload = $this->makePayload([
            'customParameters' => ['orderRef' => 'ORD-123', 'source' => 'web'],
        ]);

        $this->assertInstanceOf(CustomParameters::class, $payload->customParameters);

        $array = $payload->customParameters->toArray();
        $this->assertArrayHasKey('List', $array);
        $this->assertCount(2, $array['List']);
    }

    public function test_creates_additional_parameters_object_only(): void
    {
        $payload = $this->makePayload([
            'additionalParameters' => ['param1' => 'value1', 'param2' => 'value2'],
        ]);

        $this->assertInstanceOf(AdditionalParameters::class, $payload->additionalParameters);

        $array = $payload->additionalParameters->toArray();
        $this->assertTrue(
            isset($array['AdditionalParameter']) || isset($array['List']),
            'AdditionalParameters should contain AdditionalParameter or List'
        );
    }

    public function test_creates_client_ip_with_both_address_and_type(): void
    {
        $payload = $this->makePayload([
            'clientIP' => ['address' => '10.0.0.1', 'type' => 0],
        ]);

        $this->assertInstanceOf(ClientIP::class, $payload->clientIP);

        $array = $payload->clientIP->toArray();
        $this->assertSame('10.0.0.1', $array['Address']);
        $this->assertSame(0, $array['Type']);
    }

    public function test_combines_nested_and_flat_properties(): void
    {
        $payload = $this->makePayload([
            'currency' => 'EUR',
            'invoice' => 'INV-001',
            'customParameters' => ['orderId' => '12345'],
            'description' => 'Test payment',
            'additionalParameters' => ['meta' => 'data'],
        ]);

        $this->assertSame('EUR', $payload->currency);
        $this->assertSame('INV-001', $payload->invoice);
        $this->assertSame('Test payment', $payload->description);
        $this->assertInstanceOf(CustomParameters::class, $payload->customParameters);
        $this->assertInstanceOf(AdditionalParameters::class, $payload->additionalParameters);
    }

    public function test_unsets_nested_keys_before_parent_processing(): void
    {
        $data = [
            'customParameters' => ['key' => 'value'],
            'additionalParameters' => ['param' => 'val'],
            'clientIP' => ['address' => '127.0.0.1'],
            'currency' => 'USD',
        ];

        $payload = $this->makePayload($data);

        $this->assertSame('USD', $payload->currency);
        $this->assertInstanceOf(CustomParameters::class, $payload->customParameters);
        $this->assertInstanceOf(AdditionalParameters::class, $payload->additionalParameters);
        $this->assertInstanceOf(ClientIP::class, $payload->clientIP);
    }

    public function test_set_properties_creates_nested_objects(): void
    {
        $payload = $this->makePayload();

        $payload->setProperties([
            'customParameters' => ['key1' => 'value1'],
            'additionalParameters' => ['key2' => 'value2'],
            'clientIP' => ['address' => '192.168.0.1', 'type' => 0],
        ]);

        $this->assertInstanceOf(CustomParameters::class, $payload->customParameters);
        $this->assertInstanceOf(AdditionalParameters::class, $payload->additionalParameters);
        $this->assertInstanceOf(ClientIP::class, $payload->clientIP);
    }

    public function test_set_properties_with_partial_nested_data(): void
    {
        $payload = $this->makePayload([
            'currency' => 'EUR',
        ]);

        $payload->setProperties([
            'customParameters' => ['ref' => 'REF-001'],
        ]);

        $this->assertSame('EUR', $payload->currency);
        $this->assertInstanceOf(CustomParameters::class, $payload->customParameters);
    }

    public function test_to_array_converts_nested_arrayable_objects(): void
    {
        $payload = $this->makePayload([
            'customParameters' => ['orderRef' => 'ORD-999'],
            'additionalParameters' => ['metadata' => 'extra'],
            'currency' => 'GBP',
        ]);

        $array = $payload->toArray();

        $this->assertIsArray($array);
        $this->assertSame('GBP', $array['currency']);
        $this->assertIsArray($array['customParameters']);
        $this->assertArrayHasKey('List', $array['customParameters']);
        $this->assertIsArray($array['additionalParameters']);
    }

    public function test_to_array_includes_client_ip(): void
    {
        $payload = $this->makePayload([
            'clientIP' => ['address' => '203.0.113.1', 'type' => 0],
            'invoice' => 'INV-123',
        ]);

        $array = $payload->toArray();

        $this->assertArrayHasKey('clientIP', $array);
        $this->assertIsArray($array['clientIP']);
        $this->assertSame('203.0.113.1', $array['clientIP']['Address']);
        $this->assertSame(0, $array['clientIP']['Type']);
        $this->assertSame('INV-123', $array['invoice']);
    }

    public function test_to_array_with_all_properties(): void
    {
        $payload = $this->makePayload([
            'currency' => 'EUR',
            'returnURL' => 'https://example.com/return',
            'returnURLError' => 'https://example.com/error',
            'returnURLCancel' => 'https://example.com/cancel',
            'returnURLReject' => 'https://example.com/reject',
            'pushURL' => 'https://example.com/push',
            'pushURLFailure' => 'https://example.com/push-failure',
            'invoice' => 'INV-FULL-001',
            'description' => 'Complete payment',
            'originalTransactionKey' => 'ORIG-TX-KEY',
            'originalTransactionReference' => 'ORIG-REF',
            'websiteKey' => 'WEBSITE-KEY',
            'culture' => 'nl-NL',
            'startRecurrent' => true,
            'continueOnIncomplete' => 'RedirectToHTML',
            'servicesSelectableByClient' => 'ideal,paypal',
            'servicesExcludedForClient' => 'bancontact',
            'customParameters' => ['custom1' => 'val1'],
            'additionalParameters' => ['add1' => 'val2'],
            'clientIP' => ['address' => '192.0.2.1', 'type' => 0],
        ]);

        $array = $payload->toArray();

        $this->assertSame('EUR', $array['currency']);
        $this->assertSame('https://example.com/return', $array['returnURL']);
        $this->assertSame('https://example.com/error', $array['returnURLError']);
        $this->assertSame('https://example.com/cancel', $array['returnURLCancel']);
        $this->assertSame('https://example.com/reject', $array['returnURLReject']);
        $this->assertSame('https://example.com/push', $array['pushURL']);
        $this->assertSame('https://example.com/push-failure', $array['pushURLFailure']);
        $this->assertSame('INV-FULL-001', $array['invoice']);
        $this->assertSame('Complete payment', $array['description']);
        $this->assertSame('ORIG-TX-KEY', $array['originalTransactionKey']);
        $this->assertSame('ORIG-REF', $array['originalTransactionReference']);
        $this->assertSame('WEBSITE-KEY', $array['websiteKey']);
        $this->assertSame('nl-NL', $array['culture']);
        $this->assertTrue($array['startRecurrent']);
        $this->assertSame('RedirectToHTML', $array['continueOnIncomplete']);
        $this->assertSame('ideal,paypal', $array['servicesSelectableByClient']);
        $this->assertSame('bancontact', $array['servicesExcludedForClient']);
        $this->assertIsArray($array['customParameters']);
        $this->assertIsArray($array['additionalParameters']);
        $this->assertIsArray($array['clientIP']);
    }

    public function test_to_array_with_empty_payload(): void
    {
        $payload = $this->makePayload();

        $array = $payload->toArray();

        $this->assertIsArray($array);
    }

    public function test_multiple_set_properties_calls_with_nested_objects(): void
    {
        $payload = $this->makePayload([
            'currency' => 'EUR',
        ]);

        $payload->setProperties([
            'customParameters' => ['key1' => 'value1'],
        ]);

        $payload->setProperties([
            'additionalParameters' => ['key2' => 'value2'],
            'invoice' => 'INV-002',
        ]);

        $this->assertSame('EUR', $payload->currency);
        $this->assertSame('INV-002', $payload->invoice);
        $this->assertInstanceOf(CustomParameters::class, $payload->customParameters);
        $this->assertInstanceOf(AdditionalParameters::class, $payload->additionalParameters);
    }

    public function test_overwrites_nested_objects_on_subsequent_set_properties(): void
    {
        $payload = $this->makePayload([
            'customParameters' => ['first' => 'value1'],
        ]);

        $firstCustomParams = $payload->customParameters;
        $this->assertInstanceOf(CustomParameters::class, $firstCustomParams);

        $payload->setProperties([
            'customParameters' => ['second' => 'value2'],
        ]);

        $secondCustomParams = $payload->customParameters;
        $this->assertInstanceOf(CustomParameters::class, $secondCustomParams);
        $this->assertNotSame($firstCustomParams, $secondCustomParams);

        $array = $secondCustomParams->toArray();
        $names = array_column($array['List'], 'Name');
        $this->assertContains('second', $names);
    }

    public function test_handles_null_in_nested_client_ip_properties(): void
    {
        $payload = $this->makePayload([
            'clientIP' => ['address' => null, 'type' => null],
        ]);

        $this->assertInstanceOf(ClientIP::class, $payload->clientIP);
    }

    public function test_preserves_flat_properties_when_setting_nested_objects(): void
    {
        $payload = $this->makePayload([
            'currency' => 'EUR',
            'invoice' => 'INV-001',
            'description' => 'Initial',
        ]);

        $payload->setProperties([
            'customParameters' => ['ref' => 'REF-001'],
            'additionalParameters' => ['meta' => 'data'],
        ]);

        $this->assertSame('EUR', $payload->currency);
        $this->assertSame('INV-001', $payload->invoice);
        $this->assertSame('Initial', $payload->description);
    }

    public function test_to_array_handles_mixed_initialized_and_uninitialized_properties(): void
    {
        $payload = $this->makePayload([
            'currency' => 'EUR',
            'customParameters' => ['key' => 'value'],
        ]);

        $array = $payload->toArray();

        $this->assertArrayHasKey('currency', $array);
        $this->assertArrayHasKey('customParameters', $array);
        $this->assertSame('EUR', $array['currency']);
    }

    public function test_nested_objects_are_independent_instances(): void
    {
        $sharedData = ['shared' => 'value'];

        $payload1 = $this->makePayload(['customParameters' => $sharedData]);
        $payload2 = $this->makePayload(['customParameters' => $sharedData]);

        $this->assertNotSame($payload1->customParameters, $payload2->customParameters);
        $this->assertInstanceOf(CustomParameters::class, $payload1->customParameters);
        $this->assertInstanceOf(CustomParameters::class, $payload2->customParameters);
    }
}
