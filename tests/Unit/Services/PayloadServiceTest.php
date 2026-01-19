<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use Buckaroo\Resources\Arrayable;
use Buckaroo\Services\PayloadService;
use Exception;
use PHPUnit\Framework\TestCase;

class PayloadServiceTest extends TestCase
{
    public function test_it_implements_arrayable_interface(): void
    {
        $service = new PayloadService(['key' => 'value']);

        $this->assertInstanceOf(Arrayable::class, $service);
    }

    public function test_it_accepts_array_payload(): void
    {
        $payload = ['amount' => 10.50, 'invoice' => 'TEST-001'];

        $service = new PayloadService($payload);

        $this->assertSame($payload, $service->toArray());
    }

    public function test_it_accepts_json_string_payload(): void
    {
        $jsonPayload = '{"amount": 10.50, "invoice": "TEST-001"}';

        $service = new PayloadService($jsonPayload);

        $result = $service->toArray();

        $this->assertIsArray($result);
        $this->assertSame(10.50, $result['amount']);
        $this->assertSame('TEST-001', $result['invoice']);
    }

    public function test_it_handles_nested_json_structure(): void
    {
        $jsonPayload = '{"customer": {"firstName": "John", "lastName": "Doe"}, "amount": 25.00}';

        $service = new PayloadService($jsonPayload);

        $result = $service->toArray();

        $this->assertArrayHasKey('customer', $result);
        $this->assertSame('John', $result['customer']['firstName']);
        $this->assertSame('Doe', $result['customer']['lastName']);
        $this->assertSame(25.00, $result['amount']);
    }

    public function test_it_handles_nested_array_structure(): void
    {
        $payload = [
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
            'amount' => 25.00,
        ];

        $service = new PayloadService($payload);

        $result = $service->toArray();

        $this->assertSame($payload, $result);
    }

    public function test_it_handles_json_array(): void
    {
        $jsonPayload = '[{"id": 1}, {"id": 2}]';

        $service = new PayloadService($jsonPayload);

        $result = $service->toArray();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertSame(1, $result[0]['id']);
        $this->assertSame(2, $result[1]['id']);
    }

    public function test_it_throws_exception_for_empty_json_object(): void
    {
        // Empty JSON object {} decodes to empty array which is treated as null-ish
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid or empty payload. Array or json format required.');

        new PayloadService('{}');
    }

    public function test_it_handles_empty_array(): void
    {
        $service = new PayloadService([]);

        $this->assertSame([], $service->toArray());
    }

    public function test_it_throws_error_for_invalid_json(): void
    {
        // Invalid JSON causes TypeError when trying to assign null to typed array property
        $this->expectException(\TypeError::class);

        new PayloadService('invalid json string');
    }

    public function test_it_throws_error_for_null_payload(): void
    {
        // Null payload causes Error when checking uninitialized typed property
        $this->expectException(\Error::class);

        new PayloadService(null);
    }

    public function test_it_throws_error_for_empty_string(): void
    {
        // Empty string causes TypeError when trying to assign null to typed array property
        $this->expectException(\TypeError::class);

        new PayloadService('');
    }

    public function test_it_throws_error_for_json_null(): void
    {
        // JSON null causes TypeError when trying to assign null to typed array property
        $this->expectException(\TypeError::class);

        new PayloadService('null');
    }

    public function test_it_handles_json_with_special_characters(): void
    {
        $jsonPayload = '{"description": "Test with special chars: \\"quoted\\" and unicode: é"}';

        $service = new PayloadService($jsonPayload);

        $result = $service->toArray();

        $this->assertStringContainsString('"quoted"', $result['description']);
        $this->assertStringContainsString('é', $result['description']);
    }

    public function test_it_handles_json_with_numbers(): void
    {
        $jsonPayload = '{"integer": 42, "float": 3.14, "scientific": 1.5e10}';

        $service = new PayloadService($jsonPayload);

        $result = $service->toArray();

        $this->assertSame(42, $result['integer']);
        $this->assertSame(3.14, $result['float']);
        $this->assertSame(1.5e10, $result['scientific']);
    }

    public function test_it_handles_json_with_boolean_values(): void
    {
        $jsonPayload = '{"active": true, "deleted": false}';

        $service = new PayloadService($jsonPayload);

        $result = $service->toArray();

        $this->assertTrue($result['active']);
        $this->assertFalse($result['deleted']);
    }

    public function test_to_array_returns_same_array_multiple_times(): void
    {
        $payload = ['key' => 'value'];
        $service = new PayloadService($payload);

        $first = $service->toArray();
        $second = $service->toArray();

        $this->assertSame($first, $second);
    }
}
