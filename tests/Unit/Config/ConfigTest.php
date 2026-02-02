<?php

declare(strict_types=1);

namespace Tests\Unit\Config;

use Buckaroo\Config\DefaultConfig;
use Buckaroo\Handlers\Logging\DefaultLogger;
use Buckaroo\Handlers\Logging\Observer;
use Buckaroo\Handlers\Logging\Subject;
use Tests\TestCase;

class ConfigTest extends TestCase
{
    public function test_creates_config_with_required_parameters(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $this->assertSame('websiteKey', $config->websiteKey());
        $this->assertSame('secretKey', $config->secretKey());
    }

    public function test_defaults_to_test_mode(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $this->assertSame('test', $config->mode());
        $this->assertFalse($config->isLiveMode());
    }

    public function test_can_set_live_mode(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey', 'live');

        $this->assertSame('live', $config->mode());
        $this->assertTrue($config->isLiveMode());
    }

    public function test_defaults_currency_to_eur(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $this->assertSame('EUR', $config->currency());
    }

    public function test_sets_optional_url_parameters(): void
    {
        $config = new DefaultConfig(
            'websiteKey',
            'secretKey',
            'test',
            'EUR',
            'https://example.com/return',
            'https://example.com/cancel',
            'https://example.com/push'
        );

        $this->assertSame('https://example.com/return', $config->returnURL());
        $this->assertSame('https://example.com/cancel', $config->returnURLCancel());
        $this->assertSame('https://example.com/push', $config->pushURL());
    }

    public function test_merges_additional_config(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $config->merge([
            'currency' => 'USD',
            'returnURL' => 'https://merged.com/return',
        ]);

        $this->assertSame('USD', $config->currency());
        $this->assertSame('https://merged.com/return', $config->returnURL());
    }

    public function test_prevents_merging_credentials(): void
    {
        $config = new DefaultConfig('originalWebsiteKey', 'originalSecretKey');

        $config->merge([
            'websiteKey' => 'hackedWebsiteKey',
            'secretKey' => 'hackedSecretKey',
            'currency' => 'GBP',
        ]);

        // Credentials should NOT be changed
        $this->assertSame('originalWebsiteKey', $config->websiteKey());
        $this->assertSame('originalSecretKey', $config->secretKey());
        // Other fields should be merged
        $this->assertSame('GBP', $config->currency());
    }

    public function test_returns_default_culture(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $this->assertSame('en-GB', $config->culture());
    }

    public function test_returns_default_channel(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $this->assertSame('Web', $config->channel());
    }

    public function test_can_change_mode_via_mode_method(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey', 'test');

        $this->assertSame('test', $config->mode());

        // Change to live
        $config->mode('live');
        $this->assertSame('live', $config->mode());
        $this->assertTrue($config->isLiveMode());

        // Change back to test
        $config->mode('test');
        $this->assertSame('test', $config->mode());
        $this->assertFalse($config->isLiveMode());
    }

    public function test_ignores_invalid_mode_values(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey', 'test');

        // Try to set invalid mode
        $config->mode('invalid');

        // Should remain unchanged
        $this->assertSame('test', $config->mode());
    }

    public function test_gets_multiple_properties(): void
    {
        $config = new DefaultConfig(
            'websiteKey',
            'secretKey',
            'test',
            'EUR',
            'https://example.com/return'
        );

        $properties = $config->get(['websiteKey', 'secretKey', 'mode', 'currency']);

        $this->assertArrayHasKey('websiteKey', $properties);
        $this->assertArrayHasKey('secretKey', $properties);
        $this->assertArrayHasKey('mode', $properties);
        $this->assertArrayHasKey('currency', $properties);
        $this->assertSame('websiteKey', $properties['websiteKey']);
        $this->assertSame('test', $properties['mode']);
    }

    public function test_sets_platform_information(): void
    {
        $config = new DefaultConfig(
            'websiteKey',
            'secretKey',
            'test',
            'EUR',
            null,
            null,
            null,
            'MyPlatform',
            '2.0.0',
            'MyCompany',
            'MyModule',
            '1.5.0'
        );

        $this->assertSame('MyPlatform', $config->platformName());
        $this->assertSame('2.0.0', $config->platformVersion());
        $this->assertSame('MyCompany', $config->moduleSupplier());
        $this->assertSame('MyModule', $config->moduleName());
        $this->assertSame('1.5.0', $config->moduleVersion());
    }

    public function test_returns_null_timeout_by_default(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $this->assertNull($config->getTimeout());
        $this->assertNull($config->getConnectTimeout());
    }

    public function test_sets_timeout_values(): void
    {
        $config = new DefaultConfig(
            'websiteKey',
            'secretKey',
            'test',
            'EUR',
            null,  // returnURL
            null,  // returnURLCancel
            null,  // pushURL
            null,  // platformName
            null,  // platformVersion
            null,  // moduleSupplier
            null,  // moduleName
            null,  // moduleVersion
            null,  // culture
            null,  // channel
            null,  // logger
            30,    // timeout
            10     // connectTimeout
        );

        $this->assertSame(30, $config->getTimeout());
        $this->assertSame(10, $config->getConnectTimeout());
    }

    public function test_creates_default_logger_automatically(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $logger = $config->getLogger();

        $this->assertNotNull($logger);
        $this->assertInstanceOf(Subject::class, $logger);
        $this->assertInstanceOf(DefaultLogger::class, $logger);
    }

    public function test_accepts_custom_logger(): void
    {
        $customLogger = new DefaultLogger();

        $config = new DefaultConfig(
            'websiteKey',
            'secretKey',
            'test',
            'EUR',
            null,  // returnURL
            null,  // returnURLCancel
            null,  // pushURL
            null,  // platformName
            null,  // platformVersion
            null,  // moduleSupplier
            null,  // moduleName
            null,  // moduleVersion
            null,  // culture
            null,  // channel
            $customLogger
        );

        $this->assertSame($customLogger, $config->getLogger());
    }

    public function test_can_change_logger_via_setter(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $originalLogger = $config->getLogger();

        $newLogger = new DefaultLogger();
        $config->setLogger($newLogger);

        $this->assertNotSame($originalLogger, $config->getLogger());
        $this->assertSame($newLogger, $config->getLogger());
    }

    public function test_returns_self_from_set_logger(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $newLogger = new DefaultLogger();

        $result = $config->setLogger($newLogger);

        $this->assertSame($config, $result);
    }

    public function test_allows_logger_to_attach_observers(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $logger = $config->getLogger();

        // Create a mock observer
        $observer = new class implements Observer {
            public array $messages = [];

            public function handle(string $method, string $message, array $context = []): void
            {
                $this->messages[] = ['method' => $method, 'message' => $message, 'context' => $context];
            }
        };

        $logger->attach($observer);
        $logger->info('Test message', ['key' => 'value']);

        $this->assertCount(1, $observer->messages);
        $this->assertSame('info', $observer->messages[0]['method']);
        $this->assertSame('Test message', $observer->messages[0]['message']);
        $this->assertSame(['key' => 'value'], $observer->messages[0]['context']);
    }

    public function test_allows_logger_to_detach_observers(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $logger = $config->getLogger();

        $observer = new class implements Observer {
            public array $messages = [];

            public function handle(string $method, string $message, array $context = []): void
            {
                $this->messages[] = $message;
            }
        };

        $logger->attach($observer);
        $logger->info('First message');

        $this->assertCount(1, $observer->messages);

        $logger->detach($observer);
        $logger->info('Second message');

        // Should still be 1 since observer was detached
        $this->assertCount(1, $observer->messages);
    }

    public function test_defines_mode_constants(): void
    {
        $this->assertSame('live', DefaultConfig::LIVE_MODE);
        $this->assertSame('test', DefaultConfig::TEST_MODE);
    }

    public function test_uses_environment_variable_for_mode(): void
    {
        $_ENV['BPE_MODE'] = 'live';

        $config = new DefaultConfig('websiteKey', 'secretKey', 'test');

        // Environment variable should override constructor parameter
        $this->assertSame('live', $config->mode());
        $this->assertTrue($config->isLiveMode());
    }

    public function test_uses_environment_variable_for_currency(): void
    {
        $_ENV['BPE_CURRENCY_CODE'] = 'USD';

        $config = new DefaultConfig('websiteKey', 'secretKey', 'test', 'EUR');

        // Environment variable should override constructor parameter
        $this->assertSame('USD', $config->currency());
    }

    public function test_uses_environment_variables_for_urls(): void
    {
        $_ENV['BPE_RETURN_URL'] = 'https://env.example.com/return';
        $_ENV['BPE_RETURN_URL_CANCEL'] = 'https://env.example.com/cancel';
        $_ENV['BPE_PUSH_URL'] = 'https://env.example.com/push';

        $config = new DefaultConfig(
            'websiteKey',
            'secretKey',
            'test',
            'EUR',
            'https://constructor.example.com/return',
            'https://constructor.example.com/cancel',
            'https://constructor.example.com/push'
        );

        // Environment variables should override constructor parameters
        $this->assertSame('https://env.example.com/return', $config->returnURL());
        $this->assertSame('https://env.example.com/cancel', $config->returnURLCancel());
        $this->assertSame('https://env.example.com/push', $config->pushURL());
    }

    public function test_uses_environment_variables_for_platform_information(): void
    {
        $_ENV['PlatformName'] = 'EnvPlatform';
        $_ENV['PlatformVersion'] = '3.0.0';
        $_ENV['ModuleSupplier'] = 'EnvSupplier';
        $_ENV['ModuleName'] = 'EnvModule';
        $_ENV['ModuleVersion'] = '2.5.0';

        $config = new DefaultConfig(
            'websiteKey',
            'secretKey',
            'test',
            'EUR',
            null,
            null,
            null,
            'ConstructorPlatform',
            '1.0.0',
            'ConstructorSupplier',
            'ConstructorModule',
            '1.0.0'
        );

        // Environment variables should override constructor parameters
        $this->assertSame('EnvPlatform', $config->platformName());
        $this->assertSame('3.0.0', $config->platformVersion());
        $this->assertSame('EnvSupplier', $config->moduleSupplier());
        $this->assertSame('EnvModule', $config->moduleName());
        $this->assertSame('2.5.0', $config->moduleVersion());
    }

    public function test_uses_environment_variables_for_culture_and_channel(): void
    {
        $_ENV['Culture'] = 'nl-NL';
        $_ENV['Channel'] = 'Mobile';

        $config = new DefaultConfig(
            'websiteKey',
            'secretKey',
            'test',
            'EUR',
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            'en-GB',
            'Web'
        );

        // Environment variables should override constructor parameters
        $this->assertSame('nl-NL', $config->culture());
        $this->assertSame('Mobile', $config->channel());
    }

    public function test_falls_back_to_constructor_when_no_environment_variable(): void
    {
        // Ensure no environment variables are set
        unset(
            $_ENV['BPE_MODE'],
            $_ENV['BPE_CURRENCY_CODE'],
            $_ENV['BPE_RETURN_URL']
        );

        $config = new DefaultConfig(
            'websiteKey',
            'secretKey',
            'live',
            'USD',
            'https://example.com/return'
        );

        $this->assertSame('live', $config->mode());
        $this->assertSame('USD', $config->currency());
        $this->assertSame('https://example.com/return', $config->returnURL());
    }

    public function test_sets_custom_culture_via_constructor(): void
    {
        $config = new DefaultConfig(
            'websiteKey',
            'secretKey',
            'test',
            'EUR',
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            'nl-NL'
        );

        $this->assertSame('nl-NL', $config->culture());
    }

    public function test_sets_custom_channel_via_constructor(): void
    {
        $config = new DefaultConfig(
            'websiteKey',
            'secretKey',
            'test',
            'EUR',
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            'Mobile'
        );

        $this->assertSame('Mobile', $config->channel());
    }

    public function test_returns_self_from_merge(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $result = $config->merge(['currency' => 'GBP']);

        $this->assertSame($config, $result);
    }

    public function test_ignores_non_existent_properties_in_merge(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $config->merge([
            'currency' => 'GBP',
            'nonExistentProperty' => 'value',
            'anotherFake' => 'ignored',
        ]);

        $this->assertSame('GBP', $config->currency());
        $this->assertFalse(property_exists($config, 'nonExistentProperty'));
        $this->assertFalse(property_exists($config, 'anotherFake'));
    }

    public function test_merges_empty_array_without_errors(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');
        $originalMode = $config->mode();

        $result = $config->merge([]);

        $this->assertSame($config, $result);
        $this->assertSame($originalMode, $config->mode());
    }

    public function test_accumulates_multiple_merge_operations(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $config->merge(['currency' => 'USD']);
        $config->merge(['returnURL' => 'https://example.com']);
        $config->merge(['currency' => 'GBP']); // Override previous merge

        $this->assertSame('GBP', $config->currency());
        $this->assertSame('https://example.com', $config->returnURL());
    }

    public function test_merges_culture_and_channel_values(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $config->merge([
            'culture' => 'de-DE',
            'channel' => 'API',
        ]);

        $this->assertSame('de-DE', $config->culture());
        $this->assertSame('API', $config->channel());
    }

    public function test_gets_empty_array_when_called_with_empty_array(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $properties = $config->get([]);

        $this->assertIsArray($properties);
        $this->assertEmpty($properties);
    }

    public function test_ignores_non_existent_method_names_in_get(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $properties = $config->get(['websiteKey', 'nonExistentMethod', 'mode']);

        $this->assertArrayHasKey('websiteKey', $properties);
        $this->assertArrayHasKey('mode', $properties);
        $this->assertArrayNotHasKey('nonExistentMethod', $properties);
        $this->assertCount(2, $properties);
    }

    public function test_gets_only_valid_methods_from_mixed_input(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $properties = $config->get([
            'websiteKey',
            'invalidMethod1',
            'mode',
            'invalidMethod2',
            'currency',
        ]);

        $this->assertArrayHasKey('websiteKey', $properties);
        $this->assertArrayHasKey('mode', $properties);
        $this->assertArrayHasKey('currency', $properties);
        $this->assertArrayNotHasKey('invalidMethod1', $properties);
        $this->assertArrayNotHasKey('invalidMethod2', $properties);
        $this->assertCount(3, $properties);
        $this->assertSame('websiteKey', $properties['websiteKey']);
        $this->assertSame('test', $properties['mode']);
        $this->assertSame('EUR', $properties['currency']);
    }
}
