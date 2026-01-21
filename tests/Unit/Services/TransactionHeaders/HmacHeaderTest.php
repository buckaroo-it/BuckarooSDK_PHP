<?php

declare(strict_types=1);

namespace Tests\Unit\Services\TransactionHeaders;

use Buckaroo\Config\DefaultConfig;
use Buckaroo\Services\TransactionHeaders\DefaultHeader;
use Buckaroo\Services\TransactionHeaders\HmacHeader;
use Tests\TestCase;

class HmacHeaderTest extends TestCase
{
    public function test_appends_authorization_header_to_existing_headers(): void
    {
        $config = new DefaultConfig('testWebsiteKey', 'testSecretKey');
        $baseHeader = new DefaultHeader(['Content-Type: application/json']);

        $hmacHeader = new HmacHeader(
            $baseHeader,
            $config,
            'https://checkout.buckaroo.nl/json/Transaction',
            '{"test":"data"}',
            'POST'
        );
        $headers = $hmacHeader->getHeaders();

        $this->assertCount(2, $headers);
        $this->assertSame('Content-Type: application/json', $headers[0]);
        $this->assertStringStartsWith('Authorization: hmac ', $headers[1]);
    }

    public function test_authorization_header_contains_website_key(): void
    {
        $config = new DefaultConfig('myWebsiteKey', 'mySecretKey');
        $baseHeader = new DefaultHeader();

        $hmacHeader = new HmacHeader(
            $baseHeader,
            $config,
            'https://checkout.buckaroo.nl/json/Transaction',
            '{"test":"data"}',
            'POST'
        );
        $headers = $hmacHeader->getHeaders();

        $authHeader = str_replace('Authorization: hmac ', '', $headers[0]);
        $parts = explode(':', $authHeader);

        $this->assertSame('myWebsiteKey', $parts[0]);
    }

    public function test_authorization_header_has_four_parts(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader = new DefaultHeader();

        $hmacHeader = new HmacHeader(
            $baseHeader,
            $config,
            'https://checkout.buckaroo.nl/json/Transaction',
            '{"test":"data"}',
            'POST'
        );
        $headers = $hmacHeader->getHeaders();

        $authHeader = str_replace('Authorization: hmac ', '', $headers[0]);
        $parts = explode(':', $authHeader);

        // Format: websiteKey:hmac:nonce:timestamp
        $this->assertCount(4, $parts);
        $this->assertSame('websiteKey', $parts[0]);
        $this->assertNotEmpty($parts[1]); // hmac signature
        $this->assertNotEmpty($parts[2]); // nonce
        $this->assertIsNumeric($parts[3]); // timestamp
    }

    public function test_preserves_base_headers_from_chain(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader = new DefaultHeader([
            'Accept: application/json',
            'X-Custom-Header: value',
        ]);

        $hmacHeader = new HmacHeader(
            $baseHeader,
            $config,
            'https://checkout.buckaroo.nl/json/Transaction',
            '{"test":"data"}',
            'POST'
        );
        $headers = $hmacHeader->getHeaders();

        $this->assertCount(3, $headers);
        $this->assertSame('Accept: application/json', $headers[0]);
        $this->assertSame('X-Custom-Header: value', $headers[1]);
        $this->assertStringStartsWith('Authorization: hmac ', $headers[2]);
    }

    public function test_generates_different_hmac_for_different_content(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader1 = new DefaultHeader();
        $baseHeader2 = new DefaultHeader();

        $hmacHeader1 = new HmacHeader(
            $baseHeader1,
            $config,
            'https://checkout.buckaroo.nl/json/Transaction',
            '{"content":"first"}',
            'POST'
        );

        $hmacHeader2 = new HmacHeader(
            $baseHeader2,
            $config,
            'https://checkout.buckaroo.nl/json/Transaction',
            '{"content":"second"}',
            'POST'
        );

        $auth1 = str_replace('Authorization: hmac ', '', $hmacHeader1->getHeaders()[0]);
        $auth2 = str_replace('Authorization: hmac ', '', $hmacHeader2->getHeaders()[0]);

        $hmac1 = explode(':', $auth1)[1];
        $hmac2 = explode(':', $auth2)[1];

        $this->assertNotSame($hmac1, $hmac2);
    }

    public function test_generates_different_nonce_for_each_request(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader1 = new DefaultHeader();
        $baseHeader2 = new DefaultHeader();

        $hmacHeader1 = new HmacHeader(
            $baseHeader1,
            $config,
            'https://checkout.buckaroo.nl/json/Transaction',
            '{"test":"data"}',
            'POST'
        );

        $hmacHeader2 = new HmacHeader(
            $baseHeader2,
            $config,
            'https://checkout.buckaroo.nl/json/Transaction',
            '{"test":"data"}',
            'POST'
        );

        $auth1 = str_replace('Authorization: hmac ', '', $hmacHeader1->getHeaders()[0]);
        $auth2 = str_replace('Authorization: hmac ', '', $hmacHeader2->getHeaders()[0]);

        $nonce1 = explode(':', $auth1)[2];
        $nonce2 = explode(':', $auth2)[2];

        $this->assertNotSame($nonce1, $nonce2);
    }

    public function test_hmac_header_with_empty_content(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $baseHeader = new DefaultHeader();

        $hmacHeader = new HmacHeader(
            $baseHeader,
            $config,
            'https://checkout.buckaroo.nl/json/Transaction',
            '',
            'GET'
        );
        $headers = $hmacHeader->getHeaders();

        $this->assertCount(1, $headers);
        $this->assertStringStartsWith('Authorization: hmac ', $headers[0]);
    }

    public function test_hmac_header_with_different_http_methods(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $methods = ['POST', 'GET', 'PUT', 'DELETE'];

        foreach ($methods as $method) {
            $baseHeader = new DefaultHeader();
            $hmacHeader = new HmacHeader(
                $baseHeader,
                $config,
                'https://checkout.buckaroo.nl/json/Transaction',
                '{"test":"data"}',
                $method
            );
            $headers = $hmacHeader->getHeaders();

            $this->assertStringStartsWith(
                'Authorization: hmac ',
                $headers[0],
                "Failed for HTTP method: {$method}"
            );
        }
    }
}
