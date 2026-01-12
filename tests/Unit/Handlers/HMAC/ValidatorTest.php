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
    public function it_validates_correct_hmac_signature(): void
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
    public function it_rejects_invalid_hmac_signature(): void
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
    public function it_rejects_tampered_data(): void
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
    public function it_throws_on_validate_or_fail_with_invalid_signature(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $invalidHeader = 'test_website_key:bad_hash:nonce:timestamp';

        $validator = new Validator($config);

        $this->expectException(BuckarooException::class);
        $this->expectExceptionMessage('HMAC validation failed.');

        $validator->validateOrFail($invalidHeader, 'https://example.com', 'POST', []);
    }

    /** @test */
    public function it_returns_calculated_hash(): void
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
    public function it_returns_true_on_validate_or_fail_with_valid_signature(): void
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
    public function it_rejects_signature_with_wrong_secret_key(): void
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
    public function it_validates_with_empty_data(): void
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
    public function it_rejects_header_with_empty_segments(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $validator = new Validator($config);

        $invalidHeader = 'key::nonce:time';
        $isValid = $validator->validate($invalidHeader, 'https://example.com', 'POST', []);

        $this->assertFalse($isValid);
    }

    /** @test */
    public function it_rejects_signature_with_wrong_website_key(): void
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
    public function it_validates_get_requests(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['page' => 1];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'GET';

        $generator = new Generator($config, $data, $uri, $method);
        $header = $generator->generate();

        $validator = new Validator($config);
        $isValid = $validator->validate($header, $uri, $method, $data);

        $this->assertTrue($isValid);
    }

    /** @test */
    public function it_validates_put_requests(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['status' => 'updated'];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'PUT';

        $generator = new Generator($config, $data, $uri, $method);
        $header = $generator->generate();

        $validator = new Validator($config);
        $isValid = $validator->validate($header, $uri, $method, $data);

        $this->assertTrue($isValid);
    }

    /** @test */
    public function it_validates_delete_requests(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction/123';
        $method = 'DELETE';

        $generator = new Generator($config, $data, $uri, $method);
        $header = $generator->generate();

        $validator = new Validator($config);
        $isValid = $validator->validate($header, $uri, $method, $data);

        $this->assertTrue($isValid);
    }

    /** @test */
    public function it_rejects_on_method_mismatch(): void
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
    public function it_validates_uri_with_query_parameters(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['amount' => 10.00];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction?serviceVersion=2&test=true';
        $method = 'POST';

        $generator = new Generator($config, $data, $uri, $method);
        $header = $generator->generate();

        $validator = new Validator($config);
        $isValid = $validator->validate($header, $uri, $method, $data);

        $this->assertTrue($isValid);
    }

    /** @test */
    public function it_validates_uri_with_special_characters(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['amount' => 10.00];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction/Order 123';
        $method = 'POST';

        $generator = new Generator($config, $data, $uri, $method);
        $header = $generator->generate();

        $validator = new Validator($config);
        $isValid = $validator->validate($header, $uri, $method, $data);

        $this->assertTrue($isValid);
    }

    /** @test */
    public function it_validates_http_protocol(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['amount' => 10.00];
        $uri = 'http://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        $generator = new Generator($config, $data, $uri, $method);
        $header = $generator->generate();

        $validator = new Validator($config);
        $isValid = $validator->validate($header, $uri, $method, $data);

        $this->assertTrue($isValid);
    }

    /** @test */
    public function it_rejects_on_uri_mismatch(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['amount' => 10.00];
        $method = 'POST';

        $generator = new Generator($config, $data, 'https://api.buckaroo.nl/json/Transaction', $method);
        $header = $generator->generate();

        $validator = new Validator($config);
        $isValid = $validator->validate($header, 'https://api.buckaroo.nl/json/Different', $method, $data);

        $this->assertFalse($isValid);
    }

    /** @test */
    public function it_validates_string_data(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $stringData = 'raw-string-payload';
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        $generator = new Generator($config, $stringData, $uri, $method);
        $header = $generator->generate();

        $validator = new Validator($config);
        $isValid = $validator->validate($header, $uri, $method, $stringData);

        $this->assertTrue($isValid);
    }

    /** @test */
    public function it_validates_nested_array_data(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
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
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        $generator = new Generator($config, $nestedData, $uri, $method);
        $header = $generator->generate();

        $validator = new Validator($config);
        $isValid = $validator->validate($header, $uri, $method, $nestedData);

        $this->assertTrue($isValid);
    }

    /** @test */
    public function it_validates_unicode_in_data(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $unicodeData = [
            'description' => 'Payment 💳 支付',
            'customer' => 'محمد',
            'amount' => 10.50,
        ];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        $generator = new Generator($config, $unicodeData, $uri, $method);
        $header = $generator->generate();

        $validator = new Validator($config);
        $isValid = $validator->validate($header, $uri, $method, $unicodeData);

        $this->assertTrue($isValid);
    }
}
