<?php

declare(strict_types=1);

namespace Tests\Unit\Transaction\Response;

use Buckaroo\Transaction\Response\Response;
use Tests\TestCase;

class ResponseTest extends TestCase
{
    public function test_initializes_with_http_response_and_data(): void
    {
        $httpResponse = (object)['status' => 200];
        $data = ['Key' => 'TX-123', 'Status' => ['Code' => ['Code' => 190]]];

        $response = new Response($httpResponse, $data);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame($httpResponse, $response->getHttpResponse());
        $this->assertSame($data, $response->toArray());
    }

    public function test_returns_http_response_object(): void
    {
        $httpResponse = (object)['status' => 200, 'body' => 'test'];
        $data = ['test' => 'value'];

        $response = new Response($httpResponse, $data);

        $this->assertSame($httpResponse, $response->getHttpResponse());
        $this->assertSame(200, $response->getHttpResponse()->status);
        $this->assertSame('test', $response->getHttpResponse()->body);
    }

    public function test_converts_to_array(): void
    {
        $data = [
            'Key' => 'TX-123',
            'Status' => ['Code' => ['Code' => 190]],
            'Invoice' => 'INV-001',
            'Currency' => 'EUR',
        ];

        $response = new Response(null, $data);

        $this->assertSame($data, $response->toArray());
    }

    public function test_implements_array_access_interface(): void
    {
        $data = [
            'Key' => 'TX-456',
            'Invoice' => 'INV-002',
            'Currency' => 'USD',
        ];

        $response = new Response(null, $data);

        // Test offsetGet
        $this->assertSame('TX-456', $response['Key']);
        $this->assertSame('INV-002', $response['Invoice']);
        $this->assertSame('USD', $response['Currency']);

        // Test offsetExists
        $this->assertTrue(isset($response['Key']));
        $this->assertTrue(isset($response['Invoice']));
        $this->assertFalse(isset($response['NonExistent']));

        // Test offsetGet returns null for non-existent
        $this->assertNull($response['NonExistent']);
        $this->assertNull($response['Missing']);

        // Test offsetSet throws exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Can't set a value of a Response");
        $response['Key'] = 'NEW-VALUE';
    }

    public function test_allows_unsetting_values_via_array_access(): void
    {
        $data = ['Key' => 'TX-222', 'Invoice' => 'INV-004'];

        $response = new Response(null, $data);

        unset($response['Invoice']);

        $this->assertFalse(isset($response['Invoice']));
        $this->assertTrue(isset($response['Key']));
    }

    public function test_handles_magic_get_methods(): void
    {
        $data = [
            'TransactionKey' => 'TX-MAGIC-001',
            'PaymentKey' => 'PAY-MAGIC-001',
            'Invoice' => 'INV-MAGIC-001',
        ];

        $response = new Response(null, $data);

        // Test magic get methods work
        $this->assertSame('TX-MAGIC-001', $response->getTransactionKey());
        $this->assertSame('PAY-MAGIC-001', $response->getPaymentKey());
        $this->assertSame('INV-MAGIC-001', $response->getInvoice());

        // Test returns null for non-existent
        $this->assertNull($response->getNonExistent());
        $this->assertNull($response->getMissingField());

        // Test non-get prefixed methods throw exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Call to undefined method');
        $response->setKey('NEW-KEY');
    }

    public function test_handles_nested_data_structures(): void
    {
        $data = [
            'Status' => [
                'Code' => ['Code' => 190],
                'SubCode' => ['Code' => 'S001'],
            ],
            'Services' => [
                ['Name' => 'ideal', 'Action' => 'Pay'],
            ],
        ];

        $response = new Response(null, $data);

        $this->assertIsArray($response['Status']);
        $this->assertSame(190, $response['Status']['Code']['Code']);
        $this->assertIsArray($response['Services']);
        $this->assertSame('ideal', $response['Services'][0]['Name']);
    }

    public function test_handles_empty_data(): void
    {
        $response = new Response(null, []);

        $this->assertSame([], $response->toArray());
        $this->assertNull($response['AnyKey']);
        $this->assertFalse(isset($response['AnyKey']));
    }

    public function test_preserves_data_types_in_array(): void
    {
        $data = [
            'StringValue' => 'test',
            'IntValue' => 123,
            'FloatValue' => 45.67,
            'BoolTrue' => true,
            'BoolFalse' => false,
            'NullValue' => null,
            'ArrayValue' => ['item1', 'item2'],
        ];

        $response = new Response(null, $data);
        $array = $response->toArray();

        $this->assertSame('test', $array['StringValue']);
        $this->assertSame(123, $array['IntValue']);
        $this->assertSame(45.67, $array['FloatValue']);
        $this->assertTrue($array['BoolTrue']);
        $this->assertFalse($array['BoolFalse']);
        $this->assertNull($array['NullValue']);
        $this->assertSame(['item1', 'item2'], $array['ArrayValue']);
    }

    public function test_handles_null_http_response(): void
    {
        $data = ['Key' => 'TX-555'];

        $response = new Response(null, $data);

        $this->assertNull($response->getHttpResponse());
        $this->assertSame($data, $response->toArray());
    }

    public function test_handles_complex_http_response_object(): void
    {
        $httpResponse = (object)[
            'status' => 200,
            'headers' => ['Content-Type' => 'application/json'],
            'body' => '{"test": "data"}',
            'timestamp' => time(),
        ];

        $response = new Response($httpResponse, ['Key' => 'TX-666']);

        $retrievedHttp = $response->getHttpResponse();
        $this->assertSame(200, $retrievedHttp->status);
        $this->assertIsArray($retrievedHttp->headers);
        $this->assertSame('application/json', $retrievedHttp->headers['Content-Type']);
    }
}
