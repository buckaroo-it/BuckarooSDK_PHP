<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers\HMAC;

use Buckaroo\Config\DefaultConfig;
use Buckaroo\Exceptions\BuckarooException;
use Buckaroo\Handlers\HMAC\Generator;
use Buckaroo\Handlers\HMAC\Validator;
use Tests\TestCase;

class ValidatorTest extends TestCase
{
    /** @test */
    public function test_validates_correct_hmac_signature(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['amount' => 10.00, 'currency' => 'EUR'];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        $generator = new Generator($config, $data, $uri, $method);
        $header = $generator->generate();

        $validator = new Validator($config);
        $isValid = $validator->validate($header, $uri, $method, $data);

        $this->assertTrue($isValid);
    }

    /** @test */
    public function test_rejects_invalid_hmac_signature(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['amount' => 10.00, 'currency' => 'EUR'];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        $invalidHeader = 'test_website_key:invalid_hash_value:some-nonce:1234567890';

        $validator = new Validator($config);
        $isValid = $validator->validate($invalidHeader, $uri, $method, $data);

        $this->assertFalse($isValid);
    }

    /** @test */
    public function test_rejects_tampered_data(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $originalData = ['amount' => 10.00, 'currency' => 'EUR'];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        $generator = new Generator($config, $originalData, $uri, $method);
        $header = $generator->generate();

        $tamperedData = ['amount' => 100.00, 'currency' => 'EUR'];

        $validator = new Validator($config);
        $isValid = $validator->validate($header, $uri, $method, $tamperedData);

        $this->assertFalse($isValid);
    }

    /** @test */
    public function test_throws_on_validate_or_fail_with_invalid_signature(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $invalidHeader = 'test_website_key:bad_hash:nonce:timestamp';

        $validator = new Validator($config);

        $this->expectException(BuckarooException::class);
        $this->expectExceptionMessage('HMAC validation failed.');

        $validator->validateOrFail($invalidHeader, 'https://example.com', 'POST', []);
    }

    /** @test */
    public function test_returns_calculated_hash(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['amount' => 10.00];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        $generator = new Generator($config, $data, $uri, $method);
        $header = $generator->generate();

        $validator = new Validator($config);
        $validator->validate($header, $uri, $method, $data);

        $calculatedHash = $validator->getHash();

        $this->assertNotEmpty($calculatedHash);
        $this->assertStringContainsString('=', $calculatedHash);
    }

    /** @test */
    public function test_returns_true_on_validate_or_fail_with_valid_signature(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['amount' => 25.00, 'currency' => 'USD'];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        $generator = new Generator($config, $data, $uri, $method);
        $header = $generator->generate();

        $validator = new Validator($config);
        $result = $validator->validateOrFail($header, $uri, $method, $data);

        $this->assertTrue($result);
    }

    /** @test */
    public function test_rejects_signature_with_wrong_secret_key(): void
    {
        $data = ['amount' => 10.00];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        $configGen = new DefaultConfig('test_website_key', 'correct_secret_key');
        $generator = new Generator($configGen, $data, $uri, $method);
        $header = $generator->generate();

        $configVal = new DefaultConfig('test_website_key', 'wrong_secret_key');
        $validator = new Validator($configVal);
        $isValid = $validator->validate($header, $uri, $method, $data);

        $this->assertFalse($isValid);
    }

    /** @test */
    public function test_validates_with_empty_data(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        $generator = new Generator($config, null, $uri, $method);
        $header = $generator->generate();

        $validator = new Validator($config);
        $isValid = $validator->validate($header, $uri, $method, null);

        $this->assertTrue($isValid);
    }

    /** @test */
    public function test_rejects_header_with_empty_segments(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $validator = new Validator($config);

        $invalidHeader = 'key::nonce:time';
        $isValid = $validator->validate($invalidHeader, 'https://example.com', 'POST', []);

        $this->assertFalse($isValid);
    }

    /** @test */
    public function test_rejects_signature_with_wrong_website_key(): void
    {
        $data = ['amount' => 10.00];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        $configGen = new DefaultConfig('website_key_A', 'shared_secret');
        $generator = new Generator($configGen, $data, $uri, $method);
        $header = $generator->generate();

        $configVal = new DefaultConfig('website_key_B', 'shared_secret');
        $validator = new Validator($configVal);
        $isValid = $validator->validate($header, $uri, $method, $data);

        $this->assertFalse($isValid);
    }

    /** @test */
    public function test_validates_multiple_http_methods(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $validator = new Validator($config);

        // Test GET request
        $getData = ['page' => 1];
        $getUri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $getGenerator = new Generator($config, $getData, $getUri, 'GET');
        $getHeader = $getGenerator->generate();
        $this->assertTrue($validator->validate($getHeader, $getUri, 'GET', $getData));

        // Test PUT request
        $putData = ['status' => 'updated'];
        $putUri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $putGenerator = new Generator($config, $putData, $putUri, 'PUT');
        $putHeader = $putGenerator->generate();
        $this->assertTrue($validator->validate($putHeader, $putUri, 'PUT', $putData));

        // Test DELETE request
        $deleteData = [];
        $deleteUri = 'https://testcheckout.buckaroo.nl/json/Transaction/123';
        $deleteGenerator = new Generator($config, $deleteData, $deleteUri, 'DELETE');
        $deleteHeader = $deleteGenerator->generate();
        $this->assertTrue($validator->validate($deleteHeader, $deleteUri, 'DELETE', $deleteData));
    }

    /** @test */
    public function test_rejects_on_method_mismatch(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['amount' => 10.00];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';

        $generator = new Generator($config, $data, $uri, 'POST');
        $header = $generator->generate();

        $validator = new Validator($config);
        $isValid = $validator->validate($header, $uri, 'GET', $data);

        $this->assertFalse($isValid);
    }

    /** @test */
    public function test_validates_various_uri_formats(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $validator = new Validator($config);
        $data = ['amount' => 10.00];
        $method = 'POST';

        // Test URI with query parameters
        $queryUri = 'https://testcheckout.buckaroo.nl/json/Transaction?serviceVersion=2&test=true';
        $queryGenerator = new Generator($config, $data, $queryUri, $method);
        $queryHeader = $queryGenerator->generate();
        $this->assertTrue($validator->validate($queryHeader, $queryUri, $method, $data));

        // Test URI with special characters
        $specialUri = 'https://testcheckout.buckaroo.nl/json/Transaction/Order 123';
        $specialGenerator = new Generator($config, $data, $specialUri, $method);
        $specialHeader = $specialGenerator->generate();
        $this->assertTrue($validator->validate($specialHeader, $specialUri, $method, $data));

        // Test HTTP protocol (vs HTTPS)
        $httpUri = 'http://testcheckout.buckaroo.nl/json/Transaction';
        $httpGenerator = new Generator($config, $data, $httpUri, $method);
        $httpHeader = $httpGenerator->generate();
        $this->assertTrue($validator->validate($httpHeader, $httpUri, $method, $data));

        // Test URI mismatch rejection
        $originalUri = 'https://api.buckaroo.nl/json/Transaction';
        $mismatchGenerator = new Generator($config, $data, $originalUri, $method);
        $mismatchHeader = $mismatchGenerator->generate();
        $this->assertFalse($validator->validate($mismatchHeader, 'https://api.buckaroo.nl/json/Different', $method, $data));
    }

    /** @test */
    public function test_validates_various_data_types(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $validator = new Validator($config);
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        // Test string data
        $stringData = 'raw-string-payload';
        $stringGenerator = new Generator($config, $stringData, $uri, $method);
        $stringHeader = $stringGenerator->generate();
        $this->assertTrue($validator->validate($stringHeader, $uri, $method, $stringData));

        // Test nested array data
        $nestedData = [
            'transaction' => [
                'amount' => 10.00,
                'currency' => 'EUR',
                'items' => [
                    ['name' => 'Product A', 'price' => 5.00],
                    ['name' => 'Product B', 'price' => 5.00],
                ],
            ],
        ];
        $nestedGenerator = new Generator($config, $nestedData, $uri, $method);
        $nestedHeader = $nestedGenerator->generate();
        $this->assertTrue($validator->validate($nestedHeader, $uri, $method, $nestedData));

        // Test Unicode data
        $unicodeData = [
            'description' => 'Payment 💳 支付',
            'customer' => 'محمد',
            'amount' => 10.50,
        ];
        $unicodeGenerator = new Generator($config, $unicodeData, $uri, $method);
        $unicodeHeader = $unicodeGenerator->generate();
        $this->assertTrue($validator->validate($unicodeHeader, $uri, $method, $unicodeData));
    }
}
