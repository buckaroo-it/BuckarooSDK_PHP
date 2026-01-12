<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers\Reply;

use Buckaroo\Config\DefaultConfig;
use Buckaroo\Handlers\HMAC\Generator;
use Buckaroo\Handlers\Reply\Json;
use Error;
use Tests\TestCase;

class JsonTest extends TestCase
{
    /** @test */
    public function it_validates_correct_hmac_signature(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [
            'Transaction' => ['Key' => 'ABC123'],
            'Key' => 'ABC123',
        ];
        $uri = 'https://example.com/push';
        $method = 'POST';

        // Generate valid HMAC header
        $generator = new Generator($config, $data, $uri, $method);
        $authHeader = $generator->generate();

        $handler = new Json($config, $data, $authHeader, $uri, $method);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Valid HMAC signature should be accepted');
    }

    /** @test */
    public function it_rejects_invalid_hmac_signature(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [
            'Transaction' => ['Key' => 'ABC123'],
            'Key' => 'ABC123',
        ];
        $uri = 'https://example.com/push';

        // Use invalid auth header
        $invalidAuthHeader = $_ENV['BPE_WEBSITE_KEY'] . ':invalid_hash:nonce:12345';

        $handler = new Json($config, $data, $invalidAuthHeader, $uri);
        $isValid = $handler->validate();

        $this->assertFalse($isValid, 'Invalid HMAC signature should be rejected');
    }

    /** @test */
    public function it_rejects_tampered_data(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $originalData = [
            'Transaction' => ['Key' => 'ABC123', 'Amount' => 10.00],
            'Key' => 'ABC123',
        ];
        $uri = 'https://example.com/push';
        $method = 'POST';

        // Generate header with original data
        $generator = new Generator($config, $originalData, $uri, $method);
        $authHeader = $generator->generate();

        // Try to validate with tampered data
        $tamperedData = [
            'Transaction' => ['Key' => 'ABC123', 'Amount' => 1000.00], // Amount changed!
            'Key' => 'ABC123',
        ];

        $handler = new Json($config, $tamperedData, $authHeader, $uri, $method);
        $isValid = $handler->validate();

        $this->assertFalse($isValid, 'Tampered data should fail validation');
    }

    /** @test */
    public function it_validates_with_get_method(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Key' => 'ABC123'];
        $uri = 'https://example.com/callback';
        $method = 'GET';

        // Generate header with GET method
        $generator = new Generator($config, $data, $uri, $method);
        $authHeader = $generator->generate();

        $handler = new Json($config, $data, $authHeader, $uri, $method);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'GET method should be supported');
    }

    /** @test */
    public function it_defaults_to_post_method(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Key' => 'ABC123'];
        $uri = 'https://example.com/push';

        // Generate header with default POST method
        $generator = new Generator($config, $data, $uri, 'POST');
        $authHeader = $generator->generate();

        // Create handler without specifying method (should default to POST)
        $handler = new Json($config, $data, $authHeader, $uri);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Should default to POST method');
    }

    /** @test */
    public function it_rejects_signature_with_wrong_secret_key(): void
    {
        $data = ['Key' => 'ABC123'];
        $uri = 'https://example.com/push';
        $method = 'POST';

        // Generate header with one secret key
        $configGen = new DefaultConfig('website_key', 'correct_secret_key');
        $generator = new Generator($configGen, $data, $uri, $method);
        $authHeader = $generator->generate();

        // Try to validate with different secret key
        $configVal = new DefaultConfig('website_key', 'wrong_secret_key');
        $handler = new Json($configVal, $data, $authHeader, $uri, $method);
        $isValid = $handler->validate();

        $this->assertFalse($isValid, 'Wrong secret key should fail validation');
    }

    /** @test */
    public function it_validates_complex_nested_data(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [
            'Transaction' => [
                'Key' => 'TX123456',
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'OK'],
                ],
            ],
            'Services' => [
                [
                    'Name' => 'creditcard',
                    'Action' => 'Pay',
                    'Parameters' => [
                        ['Name' => 'CardNumber', 'Value' => '****1234'],
                    ],
                ],
            ],
            'Key' => 'TX123456',
            'Invoice' => 'INV-001',
            'AmountDebit' => 100.50,
        ];
        $uri = 'https://example.com/push';
        $method = 'POST';

        // Generate valid HMAC header
        $generator = new Generator($config, $data, $uri, $method);
        $authHeader = $generator->generate();

        $handler = new Json($config, $data, $authHeader, $uri, $method);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Complex nested data should validate correctly');
    }

    /** @test */
    public function it_validates_with_empty_data_array(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [];
        $uri = 'https://example.com/push';
        $method = 'POST';

        // Generate valid HMAC header with empty data
        $generator = new Generator($config, $data, $uri, $method);
        $authHeader = $generator->generate();

        $handler = new Json($config, $data, $authHeader, $uri, $method);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Empty data array should validate correctly');
    }

    /** @test */
    public function it_rejects_signature_with_different_uri(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Key' => 'TX123'];
        $method = 'POST';

        $generator = new Generator($config, $data, 'https://example.com/push', $method);
        $authHeader = $generator->generate();

        $handler = new Json($config, $data, $authHeader, 'https://example.com/different', $method);
        $isValid = $handler->validate();

        $this->assertFalse($isValid, 'URI tampering should be rejected');
    }

    /** @test */
    public function it_rejects_signature_with_uri_query_parameter_changes(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Key' => 'TX123'];
        $method = 'POST';

        $generator = new Generator($config, $data, 'https://example.com/push?v=1', $method);
        $authHeader = $generator->generate();

        $handler = new Json($config, $data, $authHeader, 'https://example.com/push?v=2', $method);
        $isValid = $handler->validate();

        $this->assertFalse($isValid, 'URI query parameter changes should be rejected');
    }

    /** @test */
    public function it_validates_regardless_of_uri_protocol(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Key' => 'TX123'];
        $method = 'POST';

        $generator = new Generator($config, $data, 'http://example.com/push', $method);
        $authHeader = $generator->generate();

        $handler = new Json($config, $data, $authHeader, 'https://example.com/push', $method);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Protocol is stripped during validation so http/https are equivalent');
    }

    /** @test */
    public function it_rejects_method_mismatch(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Key' => 'TX123'];
        $uri = 'https://example.com/push';

        $generator = new Generator($config, $data, $uri, 'POST');
        $authHeader = $generator->generate();

        $handler = new Json($config, $data, $authHeader, $uri, 'GET');
        $isValid = $handler->validate();

        $this->assertFalse($isValid, 'HTTP method mismatch should be rejected');
    }

    /** @test */
    public function it_rejects_signature_with_wrong_website_key(): void
    {
        $data = ['Key' => 'TX123'];
        $uri = 'https://example.com/push';
        $method = 'POST';

        $configGen = new DefaultConfig('website_key_A', 'shared_secret');
        $generator = new Generator($configGen, $data, $uri, $method);
        $authHeader = $generator->generate();

        $configVal = new DefaultConfig('website_key_B', 'shared_secret');
        $handler = new Json($configVal, $data, $authHeader, $uri, $method);
        $isValid = $handler->validate();

        $this->assertFalse($isValid, 'Wrong website key should be rejected');
    }

    /** @test */
    public function it_validates_unicode_data(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [
            'Description' => 'Payment 💳 支付',
            'Customer' => 'محمد',
            'Amount' => 10.50,
            'Key' => 'TX123',
        ];
        $uri = 'https://example.com/push';
        $method = 'POST';

        $generator = new Generator($config, $data, $uri, $method);
        $authHeader = $generator->generate();

        $handler = new Json($config, $data, $authHeader, $uri, $method);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Unicode characters should be handled correctly');
    }

    /** @test */
    public function it_validates_data_with_special_characters(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [
            'Description' => 'Order #123 - Special chars: <>"\'/\\',
            'Invoice' => 'INV-001 & Co.',
            'Key' => 'TX123',
        ];
        $uri = 'https://example.com/push';
        $method = 'POST';

        $generator = new Generator($config, $data, $uri, $method);
        $authHeader = $generator->generate();

        $handler = new Json($config, $data, $authHeader, $uri, $method);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Special characters should be handled correctly');
    }


    /** @test */
    public function it_validates_put_method(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Status' => 'updated', 'Key' => 'TX123'];
        $uri = 'https://example.com/push';
        $method = 'PUT';

        $generator = new Generator($config, $data, $uri, $method);
        $authHeader = $generator->generate();

        $handler = new Json($config, $data, $authHeader, $uri, $method);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'PUT method should be supported');
    }

    /** @test */
    public function it_validates_delete_method(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [];
        $uri = 'https://example.com/push/TX123';
        $method = 'DELETE';

        $generator = new Generator($config, $data, $uri, $method);
        $authHeader = $generator->generate();

        $handler = new Json($config, $data, $authHeader, $uri, $method);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'DELETE method should be supported');
    }

    /** @test */
    public function it_validates_uri_with_query_parameters(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Key' => 'TX123', 'Amount' => 10.00];
        $uri = 'https://example.com/push?serviceVersion=2&test=true';
        $method = 'POST';

        $generator = new Generator($config, $data, $uri, $method);
        $authHeader = $generator->generate();

        $handler = new Json($config, $data, $authHeader, $uri, $method);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'URI with query parameters should be supported');
    }


    /** @test */
    public function it_validates_data_with_numeric_values(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [
            'Amount' => 99.99,
            'StatusCode' => 190,
            'Quantity' => 5,
            'Key' => 'TX123',
        ];
        $uri = 'https://example.com/push';
        $method = 'POST';

        $generator = new Generator($config, $data, $uri, $method);
        $authHeader = $generator->generate();

        $handler = new Json($config, $data, $authHeader, $uri, $method);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Numeric values should be handled correctly');
    }


    /** @test */
    public function it_rejects_header_with_empty_segments(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Key' => 'TX123'];
        $uri = 'https://example.com/push';

        $invalidHeader = 'key::nonce:timestamp';

        $handler = new Json($config, $data, $invalidHeader, $uri);
        $isValid = $handler->validate();

        $this->assertFalse($isValid, 'Header with empty segments should be rejected');
    }

    /** @test */
    public function it_throws_for_header_with_too_few_segments(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Key' => 'TX123'];
        $uri = 'https://example.com/push';

        $invalidHeader = 'key:hash:nonce';

        $handler = new Json($config, $data, $invalidHeader, $uri);

        error_reporting(E_ERROR);
        $this->expectException(Error::class);

        $handler->validate();
    }

    /** @test */
    public function it_validates_consistently_on_multiple_calls(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Key' => 'TX123', 'Amount' => 10.00];
        $uri = 'https://example.com/push';
        $method = 'POST';

        $generator = new Generator($config, $data, $uri, $method);
        $authHeader = $generator->generate();

        $handler = new Json($config, $data, $authHeader, $uri, $method);

        $result1 = $handler->validate();
        $result2 = $handler->validate();
        $result3 = $handler->validate();

        $this->assertTrue($result1, 'First validation should succeed');
        $this->assertTrue($result2, 'Second validation should succeed');
        $this->assertTrue($result3, 'Third validation should succeed');
        $this->assertSame($result1, $result2, 'Validation should be idempotent');
        $this->assertSame($result2, $result3, 'Validation should be idempotent');
    }

    /** @test */
    public function it_validates_data_with_boolean_values(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [
            'IsTest' => true,
            'IsRecurring' => false,
            'Key' => 'TX123',
        ];
        $uri = 'https://example.com/push';
        $method = 'POST';

        $generator = new Generator($config, $data, $uri, $method);
        $authHeader = $generator->generate();

        $handler = new Json($config, $data, $authHeader, $uri, $method);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Boolean values should be handled correctly');
    }
}
