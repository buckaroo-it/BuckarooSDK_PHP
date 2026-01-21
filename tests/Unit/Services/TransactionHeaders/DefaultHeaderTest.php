<?php

declare(strict_types=1);

namespace Tests\Unit\Services\TransactionHeaders;

use Buckaroo\Services\TransactionHeaders\DefaultHeader;
use Tests\TestCase;

class DefaultHeaderTest extends TestCase
{
    public function test_returns_empty_array_when_constructed_with_no_headers(): void
    {
        $header = new DefaultHeader();

        $this->assertSame([], $header->getHeaders());
    }

    public function test_returns_empty_array_when_constructed_with_null(): void
    {
        $header = new DefaultHeader(null);

        $this->assertSame([], $header->getHeaders());
    }

    public function test_returns_provided_headers_array(): void
    {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        $header = new DefaultHeader($headers);

        $this->assertSame($headers, $header->getHeaders());
    }

    public function test_returns_single_header(): void
    {
        $headers = ['Authorization: Bearer token123'];

        $header = new DefaultHeader($headers);

        $this->assertSame($headers, $header->getHeaders());
    }

    public function test_preserves_header_order(): void
    {
        $headers = [
            'Header-A: value1',
            'Header-B: value2',
            'Header-C: value3',
        ];

        $header = new DefaultHeader($headers);

        $this->assertSame($headers, $header->getHeaders());
        $this->assertSame('Header-A: value1', $header->getHeaders()[0]);
        $this->assertSame('Header-B: value2', $header->getHeaders()[1]);
        $this->assertSame('Header-C: value3', $header->getHeaders()[2]);
    }

    public function test_handles_empty_string_headers(): void
    {
        $headers = [''];

        $header = new DefaultHeader($headers);

        $this->assertSame([''], $header->getHeaders());
    }

    public function test_handles_headers_with_special_characters(): void
    {
        $headers = [
            'X-Custom-Header: value=with;special&chars',
            'X-Unicode-Header: value-with-\u00e9',
        ];

        $header = new DefaultHeader($headers);

        $this->assertSame($headers, $header->getHeaders());
    }
}
