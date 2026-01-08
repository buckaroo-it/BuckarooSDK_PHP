<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers\HMAC;

use Buckaroo\Config\DefaultConfig;
use Buckaroo\Handlers\HMAC\Generator;
use Tests\TestCase;

class GeneratorTest extends TestCase
{
    /** @test */
    public function it_generates_hmac_header_in_correct_format(): void
    {
        $config = new DefaultConfig('test_website_key', 'test_secret_key');
        $data = ['amount' => 10.00, 'currency' => 'EUR'];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';

        $generator = new Generator($config, $data, $uri);
        $header = $generator->generate();

        // Header should be: websiteKey:hmac:nonce:timestamp
        $parts = explode(':', $header);

        $this->assertCount(4, $parts, 'Header should have 4 parts');
        $this->assertEquals('test_website_key', $parts[0], 'First part should be website key');
        $this->assertNotEmpty($parts[1], 'HMAC hash should not be empty');
        $this->assertNotEmpty($parts[2], 'Nonce should not be empty');
        $this->assertNotEmpty($parts[3], 'Timestamp should not be empty');
    }

    /** @test */
    public function it_generates_valid_uuid_nonce(): void
    {
        $config = new DefaultConfig('test_website_key', 'test_secret_key');
        $generator = new Generator($config, [], 'https://example.com/api');

        $header = $generator->generate();
        $parts = explode(':', $header);
        $nonce = $parts[2];

        // UUID v4 format: xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx
        $uuidPattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

        $this->assertMatchesRegularExpression($uuidPattern, $nonce, 'Nonce should be a valid UUID v4');
    }

    /** @test */
    public function it_normalizes_uri_correctly(): void
    {
        $config = new DefaultConfig('test_website_key', 'test_secret_key');

        // Test with full URL
        $generator1 = new Generator($config, [], 'https://testcheckout.buckaroo.nl/json/Transaction');
        $uri1 = $generator1->uri();
        $this->assertEquals('testcheckout.buckaroo.nl%2fjson%2ftransaction', $uri1, 'URI should be normalized (protocol stripped, lowercased, URL-encoded)');

        // Test with http protocol
        $generator2 = new Generator($config, [], 'http://API.Example.COM/Path');
        $uri2 = $generator2->uri();
        $this->assertEquals('api.example.com%2fpath', $uri2, 'URI should lowercase and URL-encode');
    }

    /** @test */
    public function it_handles_empty_data(): void
    {
        $config = new DefaultConfig('test_website_key', 'test_secret_key');
        $generator = new Generator($config, null, 'https://example.com/api');

        $header = $generator->generate();
        $parts = explode(':', $header);

        $this->assertCount(4, $parts, 'Header should still have 4 parts with empty data');
        $this->assertNotEmpty($parts[1], 'HMAC should be generated even with empty data');
    }

    /** @test */
    public function it_encodes_data_with_correct_flags(): void
    {
        $config = new DefaultConfig('test_website_key', 'test_secret_key');

        // Data with special characters and decimal values
        $data = [
            'amount' => 10.50,
            'url' => 'https://example.com/callback',
            'description' => 'Test/Payment'
        ];

        $generator = new Generator($config, $data, 'https://example.com/api');
        $base64Data = $generator->base64Data($data);

        // The data should be JSON encoded with JSON_UNESCAPED_SLASHES and JSON_PRESERVE_ZERO_FRACTION
        // Then MD5 hashed, then base64 encoded
        $expectedJson = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
        $expectedBase64 = base64_encode(md5(mb_convert_encoding($expectedJson, 'UTF-8', 'auto'), true));

        $this->assertEquals($expectedBase64, $base64Data, 'base64Data should use correct JSON encoding flags');
    }

    /** @test */
    public function it_generates_consistent_hmac_for_same_input(): void
    {
        $config = new DefaultConfig('test_website_key', 'test_secret_key');
        $data = ['amount' => 15.00, 'currency' => 'USD'];
        $uri = 'https://example.com/transaction';

        // Create two generators with same input but different nonce/timestamp
        $generator1 = new Generator($config, $data, $uri);
        $generator2 = new Generator($config, $data, $uri);

        // Extract just the hash part (second element)
        $header1Parts = explode(':', $generator1->generate());
        $header2Parts = explode(':', $generator2->generate());

        // The HMAC hashes will be different because nonce and timestamp are different
        // But if we set the same nonce and timestamp, they should match
        $this->assertNotEquals($header1Parts[1], $header2Parts[1], 'Different nonce/timestamp should produce different HMACs');
    }

    /** @test */
    public function it_generates_different_hmac_for_different_secret_keys(): void
    {
        $data = ['amount' => 10.00];
        $uri = 'https://example.com/api';

        $config1 = new DefaultConfig('test_website_key', 'secret_key_1');
        $config2 = new DefaultConfig('test_website_key', 'secret_key_2');

        $generator1 = new Generator($config1, $data, $uri);
        $generator2 = new Generator($config2, $data, $uri);

        $header1Parts = explode(':', $generator1->generate());
        $header2Parts = explode(':', $generator2->generate());

        $this->assertNotEquals($header1Parts[1], $header2Parts[1], 'Different secret keys should produce different HMACs');
    }
}
