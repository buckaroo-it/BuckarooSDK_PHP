<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers\Reply;

use Buckaroo\Config\DefaultConfig;
use Buckaroo\Handlers\Reply\HttpPost;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class HttpPostTest extends TestCase
{
    /** @test */
    public function it_validates_correct_brq_signature(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [
            'brq_amount' => '10.00',
            'brq_currency' => 'EUR',
            'brq_invoicenumber' => 'INV-001',
        ];

        // Generate valid signature
        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Valid brq_ signature should be accepted');
    }

    /** @test */
    public function it_includes_add_and_cust_prefixes(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [
            'brq_amount' => '15.00',
            'add_custom_field' => 'custom_value',
            'cust_customer_id' => '12345',
        ];

        // Generate signature that includes add_ and cust_ prefixes
        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Signature should include add_ and cust_ prefixed fields');
    }

    /** @test */
    public function it_handles_html_entities(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        // Data with HTML entities
        $data = [
            'brq_description' => 'Test &amp; Payment',
            'brq_amount' => '10.00',
        ];

        // Generate signature - TestHelpers uses html_entity_decode like the real handler
        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'HTML entities should be decoded before signature calculation');
    }

    /** @test */
    public function it_uses_case_insensitive_sorting(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        // Data with mixed case keys - sorting should be case-insensitive
        $data = [
            'brq_Zebra' => 'last',
            'brq_apple' => 'first',
            'brq_Banana' => 'second',
        ];

        // Generate signature
        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Keys should be sorted case-insensitively');
    }

    /** @test */
    public function it_rejects_invalid_signature(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [
            'brq_amount' => '10.00',
            'brq_currency' => 'EUR',
            'brq_signature' => 'invalid_signature_that_wont_match',
        ];

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertFalse($isValid, 'Invalid signature should be rejected');
    }

    /** @test */
    public function it_handles_mixed_case_prefixes(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        // Mix of lowercase and uppercase prefixes
        $data = [
            'brq_amount' => '30.00',
            'BRQ_CURRENCY' => 'EUR',
            'add_field' => 'value1',
            'ADD_FIELD2' => 'value2',
            'cust_id' => '123',
            'CUST_NAME' => 'John',
        ];

        // Generate signature
        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Mixed case prefixes should all be included');
    }

    /** @test */
    public function it_ignores_unknown_prefixes(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        // Data with unknown prefix
        $data = [
            'brq_amount' => '10.00',
            'unknown_field' => 'should_be_ignored',
            'random_data' => 'also_ignored',
        ];

        // Generate signature - should only include brq_ prefixed fields
        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Unknown prefixes should be ignored in signature calculation');
    }

    /** @test */
    public function it_handles_uppercase_signature_field(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [
            'BRQ_AMOUNT' => '50.00',
            'BRQ_CURRENCY' => 'USD',
        ];

        // Generate signature and add as uppercase
        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['BRQ_SIGNATURE'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Uppercase BRQ_SIGNATURE should be recognized');
    }

    /** @test */
    public function it_rejects_tampered_data(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        // Original data
        $originalData = [
            'brq_amount' => '10.00',
            'brq_currency' => 'EUR',
        ];

        // Generate signature with original data
        $signature = TestHelpers::generateHttpPostSignature($originalData);

        // Tamper with the data (change amount)
        $tamperedData = [
            'brq_amount' => '1000.00', // Changed!
            'brq_currency' => 'EUR',
            'brq_signature' => $signature,
        ];

        $handler = new HttpPost($config, $tamperedData);
        $isValid = $handler->validate();

        $this->assertFalse($isValid, 'Tampered data should fail validation');
    }

    /** @test */
    public function it_rejects_signature_with_wrong_secret_key(): void
    {
        // Generate signature with one secret key
        $data = [
            'brq_amount' => '10.00',
            'brq_currency' => 'EUR',
        ];

        $signature = TestHelpers::generateHttpPostSignature($data, 'correct_secret_key');
        $data['brq_signature'] = $signature;

        // Validate with different secret key
        $config = new DefaultConfig('test_website_key', 'wrong_secret_key');
        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertFalse($isValid, 'Signature generated with different secret key should be rejected');
    }

    /** @test */
    public function it_handles_numeric_values(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        // Values that might be numeric
        $data = [
            'brq_amount' => '99.99',
            'brq_statuscode' => '190',
            'brq_quantity' => '5',
        ];

        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Numeric string values should be handled correctly');
    }

    /** @test */
    public function it_handles_special_characters_in_values(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $data = [
            'brq_description' => 'Order #123 - Special chars: <>"\'/\\',
            'brq_amount' => '10.00',
        ];

        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Special characters in values should be handled correctly');
    }

    /** @test */
    public function it_rejects_missing_signature_field(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        // Webhook data without signature field
        $data = [
            'brq_amount' => '10.00',
            'brq_currency' => 'EUR',
            'brq_invoicenumber' => 'INV-001',
        ];

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertFalse($isValid, 'Missing signature field should fail validation');
    }

    /** @test */
    public function it_rejects_empty_signature(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $data = [
            'brq_amount' => '10.00',
            'brq_currency' => 'EUR',
            'brq_signature' => '',
        ];

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertFalse($isValid, 'Empty signature should fail validation');
    }

    /** @test */
    public function it_rejects_whitespace_only_signature(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $data = [
            'brq_amount' => '10.00',
            'brq_currency' => 'EUR',
            'brq_signature' => '   ',
        ];

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertFalse($isValid, 'Whitespace-only signature should fail validation');
    }

    /** @test */
    public function it_handles_payload_with_no_valid_fields(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        // Only signature and unknown fields (no brq_/add_/cust_ fields)
        $data = [
            'unknown_field' => 'value',
            'random_data' => 'test',
        ];

        // Generate signature for empty filtered data (just secret key)
        $signature = sha1($_ENV['BPE_SECRET_KEY']);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Payload with no valid fields should validate if signature matches');
    }

    /** @test */
    public function it_handles_unicode_characters(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $data = [
            'brq_description' => 'Café ☕ Payment',
            'brq_customer' => 'José García',
            'brq_amount' => '10.00',
        ];

        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Unicode characters should be handled correctly');
    }

    /** @test */
    public function it_handles_multibyte_characters(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $data = [
            'brq_description' => '日本語 中文 العربية',
            'brq_amount' => '25.00',
        ];

        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Multibyte characters should be handled correctly');
    }

    /** @test */
    public function it_includes_fields_with_empty_values(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $data = [
            'brq_amount' => '10.00',
            'brq_description' => '',
            'brq_currency' => 'EUR',
        ];

        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Fields with empty values should be included in signature');
    }

    /** @test */
    public function it_decodes_numeric_html_entities(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $data = [
            'brq_description' => 'Less than: &#60; Greater than: &#62;',
            'brq_amount' => '10.00',
        ];

        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Numeric HTML entities should be decoded');
    }

    /** @test */
    public function it_decodes_hex_html_entities(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $data = [
            'brq_description' => 'Less than: &#x3C; Greater than: &#x3E; Ampersand: &#x26;',
            'brq_amount' => '10.00',
        ];

        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Hexadecimal HTML entities should be decoded');
    }

    /** @test */
    public function it_decodes_mixed_html_entities(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $data = [
            'brq_description' => '&lt; &gt; &amp; &quot; &#60; &#x3C;',
            'brq_amount' => '10.00',
        ];

        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Mixed HTML entity types should all be decoded');
    }

    /** @test */
    public function it_handles_field_names_with_multiple_underscores(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $data = [
            'brq_service_some_long_field_name' => 'value',
            'brq_amount' => '10.00',
        ];

        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);
        $isValid = $handler->validate();

        $this->assertTrue($isValid, 'Field names with multiple underscores should be handled');
    }

    /** @test */
    public function it_validates_consistently_on_multiple_calls(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $data = [
            'brq_amount' => '10.00',
            'brq_currency' => 'EUR',
        ];

        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);

        $result1 = $handler->validate();
        $result2 = $handler->validate();
        $result3 = $handler->validate();

        $this->assertTrue($result1, 'First validation should succeed');
        $this->assertTrue($result2, 'Second validation should succeed');
        $this->assertTrue($result3, 'Third validation should succeed');
        $this->assertSame($result1, $result2, 'Validation should be idempotent');
        $this->assertSame($result2, $result3, 'Validation should be idempotent');
    }
}
