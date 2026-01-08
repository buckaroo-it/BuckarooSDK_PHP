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
        $config = new DefaultConfig('test_website_key', 'test_secret_key');
        $data = ['amount' => 10.00, 'currency' => 'EUR'];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        // Generate a valid HMAC header
        $generator = new Generator($config, $data, $uri, $method);
        $header = $generator->generate();

        // Validate the header
        $validator = new Validator($config);
        $isValid = $validator->validate($header, $uri, $method, $data);

        $this->assertTrue($isValid, 'Valid HMAC signature should be accepted');
    }

    /** @test */
    public function it_rejects_invalid_hmac_signature(): void
    {
        $config = new DefaultConfig('test_website_key', 'test_secret_key');
        $data = ['amount' => 10.00, 'currency' => 'EUR'];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        // Create an invalid header with wrong hash
        $invalidHeader = 'test_website_key:invalid_hash_value:some-nonce:1234567890';

        $validator = new Validator($config);
        $isValid = $validator->validate($invalidHeader, $uri, $method, $data);

        $this->assertFalse($isValid, 'Invalid HMAC signature should be rejected');
    }

    /** @test */
    public function it_rejects_tampered_data(): void
    {
        $config = new DefaultConfig('test_website_key', 'test_secret_key');
        $originalData = ['amount' => 10.00, 'currency' => 'EUR'];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        // Generate header with original data
        $generator = new Generator($config, $originalData, $uri, $method);
        $header = $generator->generate();

        // Try to validate with tampered data
        $tamperedData = ['amount' => 100.00, 'currency' => 'EUR']; // Amount changed!

        $validator = new Validator($config);
        $isValid = $validator->validate($header, $uri, $method, $tamperedData);

        $this->assertFalse($isValid, 'Tampered data should fail validation');
    }

    /** @test */
    public function it_throws_on_validate_or_fail_with_invalid_signature(): void
    {
        $config = new DefaultConfig('test_website_key', 'test_secret_key');
        $invalidHeader = 'test_website_key:bad_hash:nonce:timestamp';

        $validator = new Validator($config);

        $this->expectException(BuckarooException::class);
        $this->expectExceptionMessage('HMAC validation failed.');

        $validator->validateOrFail($invalidHeader, 'https://example.com', 'POST', []);
    }

    /** @test */
    public function it_returns_calculated_hash(): void
    {
        $config = new DefaultConfig('test_website_key', 'test_secret_key');
        $data = ['amount' => 10.00];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        // Generate a valid header
        $generator = new Generator($config, $data, $uri, $method);
        $header = $generator->generate();

        // Validate and get hash
        $validator = new Validator($config);
        $validator->validate($header, $uri, $method, $data);

        $calculatedHash = $validator->getHash();

        $this->assertNotEmpty($calculatedHash, 'Calculated hash should not be empty');
        $this->assertStringContainsString('=', $calculatedHash, 'Hash should be base64 encoded (typically contains =)');
    }

    /** @test */
    public function it_returns_true_on_validate_or_fail_with_valid_signature(): void
    {
        $config = new DefaultConfig('test_website_key', 'test_secret_key');
        $data = ['amount' => 25.00, 'currency' => 'USD'];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        // Generate valid header
        $generator = new Generator($config, $data, $uri, $method);
        $header = $generator->generate();

        // Validate with validateOrFail
        $validator = new Validator($config);
        $result = $validator->validateOrFail($header, $uri, $method, $data);

        $this->assertTrue($result, 'validateOrFail should return true for valid signature');
    }

    /** @test */
    public function it_rejects_signature_with_wrong_secret_key(): void
    {
        $data = ['amount' => 10.00];
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        // Generate header with one secret key
        $configGen = new DefaultConfig('test_website_key', 'correct_secret_key');
        $generator = new Generator($configGen, $data, $uri, $method);
        $header = $generator->generate();

        // Try to validate with different secret key
        $configVal = new DefaultConfig('test_website_key', 'wrong_secret_key');
        $validator = new Validator($configVal);
        $isValid = $validator->validate($header, $uri, $method, $data);

        $this->assertFalse($isValid, 'Signature generated with different secret key should be rejected');
    }

    /** @test */
    public function it_validates_with_empty_data(): void
    {
        $config = new DefaultConfig('test_website_key', 'test_secret_key');
        $uri = 'https://testcheckout.buckaroo.nl/json/Transaction';
        $method = 'POST';

        // Generate header with empty data
        $generator = new Generator($config, null, $uri, $method);
        $header = $generator->generate();

        // Validate with empty data
        $validator = new Validator($config);
        $isValid = $validator->validate($header, $uri, $method, null);

        $this->assertTrue($isValid, 'Empty data should validate correctly');
    }
}
