<?php

declare(strict_types=1);

namespace Tests\Unit\Config;

use Buckaroo\Config\DefaultConfig;
use Tests\TestCase;

class ConfigTest extends TestCase
{
    /** @test */
    public function it_creates_config_with_required_parameters(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $this->assertEquals('websiteKey', $config->websiteKey());
        $this->assertEquals('secretKey', $config->secretKey());
    }

    /** @test */
    public function it_defaults_to_test_mode(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $this->assertEquals('test', $config->mode());
        $this->assertFalse($config->isLiveMode());
    }

    /** @test */
    public function it_can_set_live_mode(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey', 'live');

        $this->assertEquals('live', $config->mode());
        $this->assertTrue($config->isLiveMode());
    }

    /** @test */
    public function it_defaults_currency_to_eur(): void
    {
        $config = new DefaultConfig('websiteKey', 'secretKey');

        $this->assertEquals('EUR', $config->currency());
    }
}
