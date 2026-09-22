<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers\Reply;

use Buckaroo\Config\DefaultConfig;
use Buckaroo\Exceptions\BuckarooException;
use Buckaroo\Handlers\HMAC\Generator;
use Buckaroo\Handlers\Reply\ReplyHandler;
use Exception;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class ReplyHandlerTest extends TestCase
{
    public function test_validates_json_push_notifications_with_transaction_key(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Transaction' => ['Key' => 'ABC123'], 'Key' => 'ABC123'];
        $uri = 'https://example.com/push';

        $generator = new Generator($config, $data, $uri, 'POST');
        $authHeader = $generator->generate();

        $handler = new ReplyHandler($config, $data, $authHeader, $uri);
        $handler->validate();

        $this->assertTrue($handler->isValid());
    }

    public function test_validates_json_push_notifications_with_datarequest_key(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['DataRequest' => ['Key' => 'XYZ789'], 'Key' => 'XYZ789'];
        $uri = 'https://example.com/push';

        $generator = new Generator($config, $data, $uri, 'POST');
        $authHeader = $generator->generate();

        $handler = new ReplyHandler($config, $data, $authHeader, $uri);
        $handler->validate();

        $this->assertTrue($handler->isValid());
    }

    public function test_validates_json_push_notifications_from_json_string(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Transaction' => ['Key' => 'ABC123'], 'Key' => 'ABC123'];
        $uri = 'https://example.com/push';

        $generator = new Generator($config, $data, $uri, 'POST');
        $authHeader = $generator->generate();

        $jsonString = json_encode($data);

        $handler = new ReplyHandler($config, $jsonString, $authHeader, $uri);
        $handler->validate();

        $this->assertTrue($handler->isValid());
    }

    public function test_validates_http_post_webhooks_with_brq_prefix(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['brq_amount' => '10.00'];

        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new ReplyHandler($config, $data);
        $handler->validate();

        $this->assertTrue($handler->isValid());
    }

    public function test_validates_http_post_webhooks_with_uppercase_brq_prefix(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['BRQ_AMOUNT' => '25.50'];

        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['BRQ_SIGNATURE'] = $signature;

        $handler = new ReplyHandler($config, $data);
        $handler->validate();

        $this->assertTrue($handler->isValid());
    }

    public function test_json_format_takes_precedence_over_http_post(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [
            'Transaction' => ['Key' => 'ABC123'],
            'Key' => 'ABC123',
            'brq_amount' => '10.00',
        ];
        $uri = 'https://example.com/push';

        $generator = new Generator($config, $data, $uri, 'POST');
        $authHeader = $generator->generate();

        $handler = new ReplyHandler($config, $data, $authHeader, $uri);
        $handler->validate();

        $this->assertTrue($handler->isValid());
    }

    public function test_rejects_http_post_fallback_when_json_keys_present_but_auth_missing(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [
            'Transaction' => ['Key' => 'ABC123'],
            'brq_amount' => '10.00',
        ];

        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new ReplyHandler($config, $data);
        $this->assertFalse($handler->validate()->isValid());
    }

    public function test_throws_for_unknown_format(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['unknown_field' => 'value', 'another_field' => 'data'];

        $handler = new ReplyHandler($config, $data);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No reply handler strategy applied.');

        $handler->validate();
    }

    /** @dataProvider jsonFallbackProvider */
    public function test_json_envelopes_never_fall_back_to_form_authentication(
        string $root,
        bool $encoded,
        ?string $header,
        ?string $uri
    ): void {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [
            $root => ['Invoice' => 'UNSIGNED-ORDER', 'Status' => ['Code' => ['Code' => 190]]],
            'brq_statuscode' => '690',
        ];
        $data['brq_signature'] = TestHelpers::generateHttpPostSignature($data);
        $handler = new ReplyHandler($config, $encoded ? json_encode($data) : $data, $header, $uri);

        $this->assertFalse($handler->validate()->isValid());
    }

    public static function jsonFallbackProvider(): array
    {
        $cases = [];
        foreach (['Transaction', 'DataRequest'] as $root) {
            foreach ([false, true] as $encoded) {
                foreach ([
                    'no header' => [null, 'https://example.com/push'],
                    'empty header' => ['', 'https://example.com/push'],
                    'no URI' => ['key:hash:nonce:time', null],
                ] as $name => [$header, $uri]) {
                    $cases[$root . ' ' . (int) $encoded . ' ' . $name] = [$root, $encoded, $header, $uri];
                }
            }
        }

        return $cases;
    }

    public function test_invalid_hmac_cannot_fall_back_to_a_valid_form_signature(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        foreach (['Transaction', 'DataRequest'] as $root) {
            $data = [$root => ['Key' => 'ABC123'], 'brq_amount' => '10.00'];
            $data['brq_signature'] = TestHelpers::generateHttpPostSignature($data);
            $handler = new ReplyHandler($config, $data, 'key:wrong-hash:nonce:1234567890', 'https://example.com/push');
            $this->assertFalse($handler->validate()->isValid());
        }
    }

    public function test_signed_form_field_containing_transaction_name_remains_a_form(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['brq_amount' => '10.00', 'add_Transaction' => 'reference'];
        $data['brq_signature'] = TestHelpers::generateHttpPostSignature($data);
        $handler = new ReplyHandler($config, $data, 'key:hash:nonce:time', 'https://example.com/push');
        $this->assertTrue($handler->validate()->isValid());
    }

    public function test_throws_for_empty_data_array(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $handler = new ReplyHandler($config, []);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No reply handler strategy applied.');

        $handler->validate();
    }

    public function test_throws_for_invalid_json_string(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $invalidJson = '{invalid json}';

        $handler = new ReplyHandler($config, $invalidJson, 'auth:header', 'https://example.com');

        $this->expectException(BuckarooException::class);
        $this->expectExceptionMessage('Reply data must be an array or a JSON object.');

        $handler->validate();
    }

    public function test_rejects_json_data_missing_auth_header(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Transaction' => ['Key' => 'ABC123'], 'Key' => 'ABC123'];

        $handler = new ReplyHandler($config, $data, null, 'https://example.com/push');

        $this->assertFalse($handler->validate()->isValid());
    }

    public function test_rejects_json_data_missing_uri(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Transaction' => ['Key' => 'ABC123'], 'Key' => 'ABC123'];

        $generator = new Generator($config, $data, 'https://example.com/push', 'POST');
        $authHeader = $generator->generate();

        $handler = new ReplyHandler($config, $data, $authHeader, null);

        $this->assertFalse($handler->validate()->isValid());
    }

    public function test_isValid_returns_false_before_validation(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['brq_amount' => '10.00'];

        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new ReplyHandler($config, $data);

        $this->assertFalse($handler->isValid());
    }

    public function test_isValid_updates_after_successful_validation(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['brq_amount' => '10.00'];

        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new ReplyHandler($config, $data);

        $this->assertFalse($handler->isValid());
        $handler->validate();
        $this->assertTrue($handler->isValid());
    }

    public function test_provides_case_insensitive_data_access(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['brq_amount' => '10.00', 'BRQ_CURRENCY' => 'EUR'];

        $handler = new ReplyHandler($config, $data);

        $this->assertSame('10.00', $handler->data('brq_amount'));
        $this->assertSame('10.00', $handler->data('BRQ_AMOUNT'));
        $this->assertSame('EUR', $handler->data('BRQ_CURRENCY'));
        $this->assertSame('EUR', $handler->data('brq_currency'));
    }

    public function test_returns_all_data_when_no_key_specified(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['brq_amount' => '10.00', 'brq_currency' => 'EUR'];

        $handler = new ReplyHandler($config, $data);

        $allData = $handler->data();

        $this->assertIsArray($allData);
        $this->assertArrayHasKey('brq_amount', $allData);
        $this->assertArrayHasKey('brq_currency', $allData);
    }

    public function test_returns_null_for_missing_key(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['brq_amount' => '10.00'];

        $handler = new ReplyHandler($config, $data);

        $this->assertNull($handler->data('nonexistent_key'));
    }

    public function test_allows_data_access_before_validation(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['brq_amount' => '10.00', 'brq_currency' => 'EUR'];

        $handler = new ReplyHandler($config, $data);

        $this->assertSame('10.00', $handler->data('brq_amount'));
        $this->assertIsArray($handler->data());
    }

    public function test_data_returns_original_json_string_when_string_input(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Transaction' => ['Key' => 'ABC123'], 'Key' => 'ABC123'];
        $uri = 'https://example.com/push';

        $generator = new Generator($config, $data, $uri, 'POST');
        $authHeader = $generator->generate();

        $jsonString = json_encode($data);

        $handler = new ReplyHandler($config, $jsonString, $authHeader, $uri);

        $this->assertSame($jsonString, $handler->data());
    }

    public function test_all_signature_field_casings_work_through_reply_handler(): void
    {
        $secret = 'test-only-secret';
        foreach (['brq_signature', 'BRQ_SIGNATURE', 'Brq_Signature', 'bRq_sIgNaTuRe'] as $key) {
            $payload = ['Brq_statuscode' => '190', $key => sha1('Brq_statuscode=190' . $secret)];
            $handler = new ReplyHandler(new DefaultConfig('test', $secret), $payload);
            $this->assertTrue($handler->validate()->isValid(), $key);
        }
    }
}
