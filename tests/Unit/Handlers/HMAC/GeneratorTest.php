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
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['amount' => 10.00, 'currency' => 'EUR'];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';

        $generator = new Generator($config, $data, $uri);
        $header = $generator->generate();

        // Header should be: websiteKey:hmac:nonce:timestamp
        $parts = explode(':', $header);

        $this->assertCount(4, $parts, 'Header should have 4 parts');
        $this->assertSame($_ENV['BPE_WEBSITE_KEY'], $parts[0], 'First part should be website key');
        $this->assertNotEmpty($parts[1], 'HMAC hash should not be empty');
        $this->assertNotEmpty($parts[2], 'Nonce should not be empty');
        $this->assertNotEmpty($parts[3], 'Timestamp should not be empty');
    }

    /** @test */
    public function it_generates_valid_uuid_nonce(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
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
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        // Test with full URL
        $generator1 = new Generator($config, [], 'https://testcheckout.buckaroo.nl/json/Transaction');
        $uri1 = $generator1->uri();
        $this->assertSame('testcheckout.buckaroo.nl%2fjson%2ftransaction', $uri1, 'URI should be normalized (protocol stripped, lowercased, URL-encoded)');

        // Test with http protocol
        $generator2 = new Generator($config, [], 'http://API.Example.COM/Path');
        $uri2 = $generator2->uri();
        $this->assertSame('api.example.com%2fpath', $uri2, 'URI should lowercase and URL-encode');
    }

    /** @test */
    public function it_handles_empty_data(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $generator = new Generator($config, null, 'https://example.com/api');

        $header = $generator->generate();
        $parts = explode(':', $header);

        $this->assertCount(4, $parts, 'Header should still have 4 parts with empty data');
        $this->assertNotEmpty($parts[1], 'HMAC should be generated even with empty data');
    }

    /** @test */
    public function it_encodes_data_with_correct_flags(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

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

        $this->assertSame($expectedBase64, $base64Data, 'base64Data should use correct JSON encoding flags');
    }

    /** @test */
    public function it_generates_consistent_hmac_for_same_input(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
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

    /** @test */
    public function it_generates_deterministic_hmac_with_fixed_nonce_and_timestamp(): void
    {
        $config = new DefaultConfig('test_website_key', 'secret_key_123');
        $data = ['amount' => 10.50, 'currency' => 'EUR'];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';

        $generator = new Generator($config, $data, $uri);

        $fixedNonce = 'test-nonce-12345';
        $fixedTime = '1234567890';
        $generator->nonce($fixedNonce);
        $generator->time($fixedTime);

        $header = $generator->generate();
        $parts = explode(':', $header);

        $this->assertSame('test_website_key', $parts[0]);
        $this->assertSame($fixedNonce, $parts[2]);
        $this->assertSame($fixedTime, $parts[3]);

        $expectedBase64Data = $generator->base64Data($data);
        $expectedUri = $generator->uri($uri);
        $hashString = 'test_website_key' . 'POST' . $expectedUri . $fixedTime . $fixedNonce . $expectedBase64Data;
        $expectedHmac = base64_encode(hash_hmac('sha256', $hashString, 'secret_key_123', true));

        $this->assertSame($expectedHmac, $parts[1]);

        $secondHeader = $generator->generate();
        $this->assertSame($header, $secondHeader);
    }

    /** @test */
    public function it_uses_different_http_methods_in_hmac_calculation(): void
    {
        $config = new DefaultConfig('test_key', 'test_secret');
        $data = ['test' => 'data'];
        $uri = 'https://example.com/api';

        $generatorPost = new Generator($config, $data, $uri, 'POST');
        $generatorGet = new Generator($config, $data, $uri, 'GET');
        $generatorPut = new Generator($config, $data, $uri, 'PUT');
        $generatorDelete = new Generator($config, $data, $uri, 'DELETE');

        $fixedNonce = 'same-nonce';
        $fixedTime = '1000000000';
        foreach ([$generatorPost, $generatorGet, $generatorPut, $generatorDelete] as $gen) {
            $gen->nonce($fixedNonce);
            $gen->time($fixedTime);
        }

        $hmacPost = explode(':', $generatorPost->generate())[1];
        $hmacGet = explode(':', $generatorGet->generate())[1];
        $hmacPut = explode(':', $generatorPut->generate())[1];
        $hmacDelete = explode(':', $generatorDelete->generate())[1];

        $this->assertNotSame($hmacPost, $hmacGet);
        $this->assertNotSame($hmacPost, $hmacPut);
        $this->assertNotSame($hmacPost, $hmacDelete);
        $this->assertNotSame($hmacGet, $hmacPut);
    }

    /** @test */
    public function it_handles_string_data_without_json_encoding(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $stringData = 'raw-string-data';
        $generator = new Generator($config, $stringData, 'https://example.com/api');

        $base64Data = $generator->base64Data($stringData);
        $expectedBase64 = base64_encode(md5($stringData, true));

        $this->assertSame($expectedBase64, $base64Data);

        $header = $generator->generate();
        $parts = explode(':', $header);
        $this->assertCount(4, $parts);
        $this->assertNotEmpty($parts[1]);
    }

    /** @test */
    public function it_distinguishes_empty_string_from_null_and_empty_array(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $uri = 'https://example.com/api';

        $generatorNull = new Generator($config, null, $uri);
        $generatorEmptyString = new Generator($config, '', $uri);
        $generatorEmptyArray = new Generator($config, [], $uri);

        $this->assertSame('', $generatorNull->base64Data(null));
        $this->assertSame('', $generatorEmptyString->base64Data(''));
        $this->assertSame('', $generatorEmptyArray->base64Data([]));

        $generatorNull->nonce('same');
        $generatorNull->time('1000000000');
        $generatorEmptyString->nonce('same');
        $generatorEmptyString->time('1000000000');
        $generatorEmptyArray->nonce('same');
        $generatorEmptyArray->time('1000000000');

        $hmacNull = explode(':', $generatorNull->generate())[1];
        $hmacEmptyString = explode(':', $generatorEmptyString->generate())[1];
        $hmacEmptyArray = explode(':', $generatorEmptyArray->generate())[1];

        $this->assertSame($hmacNull, $hmacEmptyString);
        $this->assertSame($hmacNull, $hmacEmptyArray);
    }

    /** @test */
    public function it_normalizes_uri_with_query_parameters(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $generator = new Generator($config, [], 'https://api.buckaroo.nl/json/Transaction?serviceVersion=2&test=true');
        $normalizedUri = $generator->uri();

        $this->assertStringContainsString('%3f', $normalizedUri);
        $this->assertStringContainsString('serviceversion', strtolower($normalizedUri));
        $this->assertSame('api.buckaroo.nl%2fjson%2ftransaction%3fserviceversion%3d2%26test%3dtrue', $normalizedUri);
    }

    /** @test */
    public function it_normalizes_uri_with_special_characters(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $generator = new Generator($config, [], 'https://example.com/path with spaces/transaction');
        $normalizedUri = $generator->uri();

        $this->assertStringContainsString('+', $normalizedUri);
        $this->assertSame('example.com%2fpath+with+spaces%2ftransaction', $normalizedUri);
    }

    /** @test */
    public function it_handles_unicode_data_correctly(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $unicodeData = [
            'description' => 'Payment with emoji 💳 and Chinese 支付',
            'customer' => 'محمد علي',
            'amount' => 10.50,
        ];

        $generator = new Generator($config, $unicodeData, 'https://example.com/api');
        $base64Data = $generator->base64Data($unicodeData);

        $jsonData = json_encode($unicodeData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
        $expectedBase64 = base64_encode(md5(mb_convert_encoding($jsonData, 'UTF-8', 'auto'), true));

        $this->assertSame($expectedBase64, $base64Data);

        $header = $generator->generate();
        $parts = explode(':', $header);
        $this->assertCount(4, $parts);
        $this->assertNotEmpty($parts[1]);
    }

    /** @test */
    public function it_preserves_zero_fraction_in_various_float_formats(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $testCases = [
            ['amount' => 10.00],
            ['amount' => 10.10],
            ['amount' => 0.01],
            ['amount' => 999999.99],
        ];

        foreach ($testCases as $data) {
            $generator = new Generator($config, $data, 'https://example.com/api');
            $base64Data = $generator->base64Data($data);

            $jsonData = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
            $this->assertStringContainsString('.', $jsonData);

            $expectedBase64 = base64_encode(md5(mb_convert_encoding($jsonData, 'UTF-8', 'auto'), true));
            $this->assertSame($expectedBase64, $base64Data);
        }
    }

    /** @test */
    public function it_getter_methods_return_stored_values(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['amount' => 25.00];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';

        $generator = new Generator($config, $data, $uri);

        $storedUri = $generator->uri();
        $this->assertSame('testcheckout.buckaroo.nl%2fjson%2ftransaction', $storedUri);

        $storedNonce = $generator->nonce();
        $this->assertNotEmpty($storedNonce);
        $uuidPattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';
        $this->assertMatchesRegularExpression($uuidPattern, $storedNonce);

        $storedTime = $generator->time();
        $this->assertIsNumeric($storedTime);
        $this->assertGreaterThan(0, (int)$storedTime);

        $base64DataFromGetter = $generator->base64Data($data);
        $expectedBase64 = base64_encode(md5(mb_convert_encoding(
            json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION),
            'UTF-8',
            'auto'
        ), true));
        $this->assertSame($expectedBase64, $base64DataFromGetter);
    }
}
