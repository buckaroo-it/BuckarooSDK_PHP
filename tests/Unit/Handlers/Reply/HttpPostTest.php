<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers\Reply;

use Buckaroo\Config\DefaultConfig;
use Buckaroo\Handlers\Reply\HttpPost;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class HttpPostTest extends TestCase
{
    /** Dummy secret for the hand-rolled signature-security vectors below; not a real key. */
    private const SECRET = 'test-only-secret';

    public function test_validates_correct_brq_signature(): void
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

    public function test_includes_add_and_cust_prefixes(): void
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

    public function test_decodes_all_html_entity_types(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        // Test named HTML entities
        $namedData = [
            'brq_description' => 'Test &amp; Payment',
            'brq_amount' => '10.00',
        ];
        $namedSignature = TestHelpers::generateHttpPostSignature($namedData);
        $namedData['brq_signature'] = $namedSignature;
        $namedHandler = new HttpPost($config, $namedData);
        $this->assertTrue($namedHandler->validate(), 'Named HTML entities should be decoded');

        // Test numeric HTML entities
        $numericData = [
            'brq_description' => 'Less than: &#60; Greater than: &#62;',
            'brq_amount' => '10.00',
        ];
        $numericSignature = TestHelpers::generateHttpPostSignature($numericData);
        $numericData['brq_signature'] = $numericSignature;
        $numericHandler = new HttpPost($config, $numericData);
        $this->assertTrue($numericHandler->validate(), 'Numeric HTML entities should be decoded');

        // Test hexadecimal HTML entities
        $hexData = [
            'brq_description' => 'Less than: &#x3C; Greater than: &#x3E; Ampersand: &#x26;',
            'brq_amount' => '10.00',
        ];
        $hexSignature = TestHelpers::generateHttpPostSignature($hexData);
        $hexData['brq_signature'] = $hexSignature;
        $hexHandler = new HttpPost($config, $hexData);
        $this->assertTrue($hexHandler->validate(), 'Hexadecimal HTML entities should be decoded');

        // Test mixed HTML entity types
        $mixedData = [
            'brq_description' => '&lt; &gt; &amp; &quot; &#60; &#x3C;',
            'brq_amount' => '10.00',
        ];
        $mixedSignature = TestHelpers::generateHttpPostSignature($mixedData);
        $mixedData['brq_signature'] = $mixedSignature;
        $mixedHandler = new HttpPost($config, $mixedData);
        $this->assertTrue($mixedHandler->validate(), 'Mixed HTML entity types should all be decoded');
    }

    public function test_uses_case_insensitive_sorting(): void
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

    public function test_rejects_invalid_signature(): void
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

    public function test_handles_mixed_case_prefixes(): void
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

    public function test_ignores_unknown_prefixes(): void
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

    public function test_handles_uppercase_signature_field(): void
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

    public function test_rejects_tampered_data(): void
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

    public function test_rejects_signature_with_wrong_secret_key(): void
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

    public function test_handles_numeric_values(): void
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

    public function test_handles_special_characters_in_values(): void
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

    public function test_rejects_invalid_or_missing_signatures(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        // Test missing signature field
        $missingData = [
            'brq_amount' => '10.00',
            'brq_currency' => 'EUR',
            'brq_invoicenumber' => 'INV-001',
        ];
        $missingHandler = new HttpPost($config, $missingData);
        $this->assertFalse($missingHandler->validate(), 'Missing signature field should fail validation');

        // Test empty signature
        $emptyData = [
            'brq_amount' => '10.00',
            'brq_currency' => 'EUR',
            'brq_signature' => '',
        ];
        $emptyHandler = new HttpPost($config, $emptyData);
        $this->assertFalse($emptyHandler->validate(), 'Empty signature should fail validation');

        // Test whitespace-only signature
        $whitespaceData = [
            'brq_amount' => '10.00',
            'brq_currency' => 'EUR',
            'brq_signature' => '   ',
        ];
        $whitespaceHandler = new HttpPost($config, $whitespaceData);
        $this->assertFalse($whitespaceHandler->validate(), 'Whitespace-only signature should fail validation');
    }

    public function test_handles_payload_with_no_valid_fields(): void
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

    public function test_handles_unicode_and_multibyte_characters(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        // Test common Unicode characters (accents, symbols)
        $unicodeData = [
            'brq_description' => 'Café ☕ Payment',
            'brq_customer' => 'José García',
            'brq_amount' => '10.00',
        ];
        $unicodeSignature = TestHelpers::generateHttpPostSignature($unicodeData);
        $unicodeData['brq_signature'] = $unicodeSignature;
        $unicodeHandler = new HttpPost($config, $unicodeData);
        $this->assertTrue($unicodeHandler->validate(), 'Unicode characters should be handled correctly');

        // Test multibyte characters (CJK, Arabic)
        $multibyteData = [
            'brq_description' => '日本語 中文 العربية',
            'brq_amount' => '25.00',
        ];
        $multibyteSignature = TestHelpers::generateHttpPostSignature($multibyteData);
        $multibyteData['brq_signature'] = $multibyteSignature;
        $multibyteHandler = new HttpPost($config, $multibyteData);
        $this->assertTrue($multibyteHandler->validate(), 'Multibyte characters should be handled correctly');
    }

    public function test_includes_fields_with_empty_values(): void
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

    public function test_handles_field_names_with_multiple_underscores(): void
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

    /**
     * Regression vectors modelled on real Buckaroo push traffic (synthetic values).
     *
     * @return array<string, array{0: string, 1: string}>
     */
    public function gatewayPushShapeProvider(): array
    {
        $paypal = 'ADD_initiated_by_magento=1&ADD_service_action_from_magento=pay'
            . '&brq_amount=26.62&brq_currency=EUR&brq_customer_name=Test+Person&brq_description=Order+100000001'
            . '&brq_invoicenumber=100000001&brq_mutationtype=Processing&brq_ordernumber=100000001'
            . '&brq_payment=00000000000000000000000000000001'
            . '&brq_SERVICE_paypal_address_line_1=Example+Street+1&brq_SERVICE_paypal_admin_area_2=Example+City'
            . '&brq_SERVICE_paypal_CustomerName=Test+Person&brq_SERVICE_paypal_orderId=EXAMPLEPPORDER1'
            . '&brq_SERVICE_paypal_payerCountry=NL&brq_SERVICE_paypal_payerEmail=payer%40example.com'
            . '&brq_SERVICE_paypal_payerFirstname=Test&brq_SERVICE_paypal_payerLastname=Person'
            . '&brq_SERVICE_paypal_paypalCaptureId=EXAMPLECAPTURE1&brq_SERVICE_paypal_paypalTransactionID=EXAMPLEPPORDER1'
            . '&brq_SERVICE_paypal_postal_code=1234AB&brq_SERVICE_paypal_ProtectionEligibility=Eligible'
            . '&brq_SERVICE_paypal_ProtectionEligibilityType=ItemNotReceivedEligible%2cUnauthorizedPaymentEligible'
            . '&brq_SERVICE_paypal_VersionAsProperty=2&brq_statuscode=190&brq_statuscode_detail=S990'
            . '&brq_statusmessage=The+request+was+successful.&brq_test=true&brq_timestamp=2026-01-01+00%3a00%3a00'
            . '&brq_transaction_method=paypal&brq_transaction_type=V010'
            . '&brq_transactions=00000000000000000000000000000002&brq_websitekey=EXAMPLEKEY01';

        $ideal = 'ADD_initiated_by_magento=1&ADD_service_action_from_magento=payremainder'
            . '&brq_amount=11.62&brq_currency=EUR&brq_customer_name=T%c3%a8st+Person&brq_description=Order+100000002'
            . '&brq_invoicenumber=100000002&brq_mutationtype=Collecting&brq_ordernumber=100000002'
            . '&brq_payer_hash=00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000'
            . '&brq_payment=00000000000000000000000000000003'
            . '&brq_relatedtransaction_partialpayment=00000000000000000000000000000004'
            . '&brq_SERVICE_ideal_consumerBIC=BANKNL2A&brq_SERVICE_ideal_consumerIBAN=NL00BANK0123456789'
            . '&brq_SERVICE_ideal_consumerIssuer=Test+Bank&brq_SERVICE_ideal_consumerName=T%c3%a8st+Person'
            . '&brq_SERVICE_ideal_transactionId=0000000000000001&brq_statuscode=190&brq_statuscode_detail=S990'
            . '&brq_statusmessage=The+request+was+successful.&brq_test=true&brq_timestamp=2026-01-01+00%3a00%3a00'
            . '&brq_transaction_method=ideal&brq_transaction_type=C021'
            . '&brq_transactions=00000000000000000000000000000005&brq_websitekey=EXAMPLEKEY01'
            . '&CUST_CustomerBillingCity=Example+City&CUST_CustomerBillingCountry=Netherlands'
            . '&CUST_CustomerBillingEmail=payer%40example.com&CUST_CustomerBillingFirstName=Test'
            . '&CUST_CustomerBillingHouseNumber=41&CUST_CustomerBillingLastName=Person'
            . '&CUST_CustomerBillingPostcode=1234+AB&CUST_CustomerBillingStreet=Example+Street'
            . '&CUST_CustomerBillingTelephone=0600000000&CUST_CustomerShippingCity=Example+City'
            . '&CUST_CustomerShippingCountry=Netherlands&CUST_CustomerShippingEmail=payer%40example.com'
            . '&CUST_CustomerShippingFirstName=Test&CUST_CustomerShippingHouseNumber=41'
            . '&CUST_CustomerShippingLastName=Person&CUST_CustomerShippingPostcode=1234+AB'
            . '&CUST_CustomerShippingStreet=Example+Street&CUST_CustomerShippingTelephone=0600000000';

        return [
            'PayPal (native snake_case service fields, ADD_ prefix)' => [$paypal, '576c06ad5797eeb2e212b07f759c05a13df5424a'],
            'iDEAL pay-remainder (full CUST_ block, UTF-8)' => [$ideal, '2a69597bdfaf67e0ba6cec1c7fc8ec84b4d0656b'],
        ];
    }

    /**
     * @dataProvider gatewayPushShapeProvider
     */
    public function test_validates_gateway_push_shape(string $body, string $signature): void
    {
        $config = new DefaultConfig('test-website', 'golden-secret-not-a-real-key');
        $data = $this->parseFormBody($body);
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);

        $this->assertTrue($handler->validate(), 'A push in this gateway shape must validate against the pinned signature');
    }

    /**
     * @dataProvider gatewayPushShapeProvider
     */
    public function test_tampering_a_gateway_push_shape_fails(string $body, string $signature): void
    {
        $config = new DefaultConfig('test-website', 'golden-secret-not-a-real-key');
        $data = $this->parseFormBody($body);
        $data['brq_amount'] = '9999.00'; // tamper
        $data['brq_signature'] = $signature;

        $handler = new HttpPost($config, $data);

        $this->assertFalse($handler->validate(), 'Tampering any signed field must break validation');
    }

    /**
     * Parse an application/x-www-form-urlencoded body the way the platform delivers it to
     * the SDK: values url-decoded, original key names preserved.
     *
     * @return array<string, string>
     */
    private function parseFormBody(string $body): array
    {
        $data = [];
        foreach (explode('&', $body) as $pair) {
            [$key, $value] = array_pad(explode('=', $pair, 2), 2, '');
            $data[urldecode($key)] = urldecode($value);
        }

        return $data;
    }

    /* ---------------------------------------------------------------------
     * Signature-security invariants (mixed-case prefixes / collisions)
     * ------------------------------------------------------------------ */

    public function test_all_prefix_case_variants_are_signed_with_original_names(): void
    {
        foreach (['brq', 'add', 'cust'] as $prefix) {
            for ($mask = 0; $mask < (1 << strlen($prefix)); $mask++) {
                $variant = $prefix;
                for ($index = 0; $index < strlen($prefix); $index++) {
                    if ($mask & (1 << $index)) {
                        $variant[$index] = strtoupper($variant[$index]);
                    }
                }
                $key = $variant . '_reference';
                $payload = [$key => 'original', 'brq_signature' => sha1($key . '=original' . self::SECRET)];
                $this->assertTrue($this->validateSecurityPayload($payload), $key);
                $payload[$key] = 'changed';
                $this->assertFalse($this->validateSecurityPayload($payload), $key);
            }
        }
    }

    public function test_collisions_fail_even_when_both_values_are_signed(): void
    {
        foreach (['brq', 'add', 'cust'] as $prefix) {
            foreach ([false, true] as $reverse) {
                $keys = [$prefix . '_reference', ucfirst($prefix) . '_reference'];
                if ($reverse) {
                    $keys = array_reverse($keys);
                }
                $payload = [$keys[0] => 'original', $keys[1] => 'override'];
                $payload['brq_signature'] = sha1($keys[0] . '=original' . $keys[1] . '=override' . self::SECRET);
                $this->assertFalse($this->validateSecurityPayload($payload), implode(', ', $keys));
            }
        }
    }

    public function test_duplicate_signatures_and_unknown_key_collisions_are_rejected(): void
    {
        $payload = ['brq_statuscode' => '190', 'brq_signature' => sha1('brq_statuscode=190' . self::SECRET)];
        foreach ([['BRQ_SIGNATURE' => $payload['brq_signature']], ['other' => 'a', 'OTHER' => 'b']] as $extra) {
            $this->assertFalse($this->validateSecurityPayload($payload + $extra));
            $this->assertFalse($this->validateSecurityPayload(array_reverse($payload + $extra, true)));
        }
    }

    public function test_unsigned_mixed_case_override_is_rejected(): void
    {
        $payload = [
            'brq_statuscode' => '690',
            'brq_signature' => sha1('brq_statuscode=690' . self::SECRET),
            'Brq_statuscode' => '190',
        ];
        $this->assertFalse($this->validateSecurityPayload($payload));
        $this->assertFalse($this->validateSecurityPayload(array_reverse($payload, true)));
    }

    private function validateSecurityPayload(array $payload): bool
    {
        return (new HttpPost(new DefaultConfig('test', self::SECRET), $payload))->validate();
    }
}
