<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers\Reply;

use Buckaroo\Config\DefaultConfig;
use Buckaroo\Handlers\HMAC\Generator;
use Buckaroo\Handlers\Reply\HttpPost;
use Buckaroo\Handlers\Reply\Json;
use Buckaroo\Handlers\Reply\ReplyHandler;
use Exception;
use ReflectionClass;
use Tests\Support\TestHelpers;
use Tests\TestCase;
use TypeError;

/**
 * Tests ReplyHandler's strategy selection, data access, and state management.
 * Signature validation logic is tested in HttpPostTest and JsonTest.
 */
class ReplyHandlerTest extends TestCase
{
    private function getStrategy(ReplyHandler $handler): ?object
    {
        $reflection = new ReflectionClass($handler);
        $property = $reflection->getProperty('strategy');
        $property->setAccessible(true);

        return $property->isInitialized($handler) ? $property->getValue($handler) : null;
    }

    /** @test */
    public function it_selects_json_strategy_for_transaction_key(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Transaction' => ['Key' => 'ABC123'], 'Key' => 'ABC123'];
        $uri = 'https://example.com/push';

        $generator = new Generator($config, $data, $uri, 'POST');
        $authHeader = $generator->generate();

        $handler = new ReplyHandler($config, $data, $authHeader, $uri);
        $handler->validate();

        $this->assertInstanceOf(Json::class, $this->getStrategy($handler));
    }

    /** @test */
    public function it_selects_json_strategy_for_datarequest_key(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['DataRequest' => ['Key' => 'XYZ789'], 'Key' => 'XYZ789'];
        $uri = 'https://example.com/push';

        $generator = new Generator($config, $data, $uri, 'POST');
        $authHeader = $generator->generate();

        $handler = new ReplyHandler($config, $data, $authHeader, $uri);
        $handler->validate();

        $this->assertInstanceOf(Json::class, $this->getStrategy($handler));
    }

    /** @test */
    public function it_selects_http_post_strategy_for_brq_prefix(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['brq_amount' => '10.00', 'brq_signature' => 'test'];

        $handler = new ReplyHandler($config, $data);
        $handler->validate();

        $this->assertInstanceOf(HttpPost::class, $this->getStrategy($handler));
    }

    /** @test */
    public function it_selects_http_post_strategy_for_uppercase_brq_prefix(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['BRQ_AMOUNT' => '25.50', 'BRQ_SIGNATURE' => 'test'];

        $handler = new ReplyHandler($config, $data);
        $handler->validate();

        $this->assertInstanceOf(HttpPost::class, $this->getStrategy($handler));
    }

    /** @test */
    public function it_selects_json_strategy_for_json_string_input(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Transaction' => ['Key' => 'ABC123'], 'Key' => 'ABC123'];
        $uri = 'https://example.com/push';

        $generator = new Generator($config, $data, $uri, 'POST');
        $authHeader = $generator->generate();

        $jsonString = json_encode($data);

        $handler = new ReplyHandler($config, $jsonString, $authHeader, $uri);
        $handler->validate();

        $this->assertInstanceOf(Json::class, $this->getStrategy($handler));
    }

    /** @test */
    public function it_json_strategy_takes_precedence_over_http_post(): void
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

        $this->assertInstanceOf(Json::class, $this->getStrategy($handler));
    }

    /** @test */
    public function it_falls_back_to_http_post_when_json_keys_present_but_auth_missing(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [
            'Transaction' => ['Key' => 'ABC123'],
            'brq_amount' => '10.00',
            'brq_signature' => 'test',
        ];

        $handler = new ReplyHandler($config, $data);
        $handler->validate();

        $this->assertInstanceOf(HttpPost::class, $this->getStrategy($handler));
    }

    /** @test */
    public function it_selects_json_strategy_for_both_transaction_and_datarequest(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = [
            'Transaction' => ['Key' => 'TX123'],
            'DataRequest' => ['Key' => 'DR456'],
            'Key' => 'TX123',
        ];
        $uri = 'https://example.com/push';

        $generator = new Generator($config, $data, $uri, 'POST');
        $authHeader = $generator->generate();

        $handler = new ReplyHandler($config, $data, $authHeader, $uri);
        $handler->validate();

        $this->assertInstanceOf(Json::class, $this->getStrategy($handler));
    }

    /** @test */
    public function it_throws_for_unknown_format(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['unknown_field' => 'value', 'another_field' => 'data'];

        $handler = new ReplyHandler($config, $data);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No reply handler strategy applied.');

        $handler->validate();
    }

    /** @test */
    public function it_throws_for_empty_data_array(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        $handler = new ReplyHandler($config, []);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No reply handler strategy applied.');

        $handler->validate();
    }

    /** @test */
    public function it_throws_for_invalid_json_string(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $invalidJson = '{invalid json}';

        $handler = new ReplyHandler($config, $invalidJson, 'auth:header', 'https://example.com');

        $this->expectException(TypeError::class);

        $handler->validate();
    }

    /** @test */
    public function it_throws_when_json_data_missing_auth_header(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Transaction' => ['Key' => 'ABC123'], 'Key' => 'ABC123'];

        $handler = new ReplyHandler($config, $data, null, 'https://example.com/push');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No reply handler strategy applied.');

        $handler->validate();
    }

    /** @test */
    public function it_throws_when_json_data_missing_uri(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['Transaction' => ['Key' => 'ABC123'], 'Key' => 'ABC123'];

        $generator = new Generator($config, $data, 'https://example.com/push', 'POST');
        $authHeader = $generator->generate();

        $handler = new ReplyHandler($config, $data, $authHeader, null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No reply handler strategy applied.');

        $handler->validate();
    }

    /** @test */
    public function it_returns_false_before_validation(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['brq_amount' => '10.00'];

        $signature = TestHelpers::generateHttpPostSignature($data);
        $data['brq_signature'] = $signature;

        $handler = new ReplyHandler($config, $data);

        $this->assertFalse($handler->isValid());
    }

    /** @test */
    public function it_updates_validity_after_validation(): void
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

    /** @test */
    public function it_provides_case_insensitive_data_access(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['brq_amount' => '10.00', 'BRQ_CURRENCY' => 'EUR'];

        $handler = new ReplyHandler($config, $data);

        $this->assertSame('10.00', $handler->data('brq_amount'));
        $this->assertSame('10.00', $handler->data('BRQ_AMOUNT'));
        $this->assertSame('EUR', $handler->data('BRQ_CURRENCY'));
        $this->assertSame('EUR', $handler->data('brq_currency'));
    }

    /** @test */
    public function it_returns_all_data_when_no_key_specified(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['brq_amount' => '10.00', 'brq_currency' => 'EUR'];

        $handler = new ReplyHandler($config, $data);

        $allData = $handler->data();

        $this->assertIsArray($allData);
        $this->assertArrayHasKey('brq_amount', $allData);
        $this->assertArrayHasKey('brq_currency', $allData);
    }

    /** @test */
    public function it_returns_null_for_missing_key(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['brq_amount' => '10.00'];

        $handler = new ReplyHandler($config, $data);

        $this->assertNull($handler->data('nonexistent_key'));
    }

    /** @test */
    public function it_allows_data_access_before_validation(): void
    {
        $config = new DefaultConfig($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
        $data = ['brq_amount' => '10.00', 'brq_currency' => 'EUR'];

        $handler = new ReplyHandler($config, $data);

        $this->assertSame('10.00', $handler->data('brq_amount'));
        $this->assertIsArray($handler->data());
    }

    /** @test */
    public function it_data_returns_original_json_string_when_string_input(): void
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
}
