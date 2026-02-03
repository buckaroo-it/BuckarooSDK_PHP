<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Subscriptions\Models;

use Buckaroo\PaymentMethods\Subscriptions\Models\Configuration;
use Tests\TestCase;

class ConfigurationTest extends TestCase
{
    /** @test */
    public function it_sets_name(): void
    {
        $config = new Configuration(['name' => 'Test Configuration']);

        $this->assertSame('Test Configuration', $config->name);
    }

    /** @test */
    public function it_sets_scheme_key(): void
    {
        $config = new Configuration(['schemeKey' => 'SCHEME-123']);

        $this->assertSame('SCHEME-123', $config->schemeKey);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $config = new Configuration([
            'name' => 'Premium Config',
            'schemeKey' => 'SCHEME-456',
        ]);

        $this->assertSame('Premium Config', $config->name);
        $this->assertSame('SCHEME-456', $config->schemeKey);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $config = new Configuration([
            'name' => 'Array Test Config',
            'schemeKey' => 'SCHEME-789',
        ]);

        $array = $config->toArray();

        $this->assertIsArray($array);
        $this->assertSame('Array Test Config', $array['name']);
        $this->assertSame('SCHEME-789', $array['schemeKey']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $config = new Configuration([]);

        $array = $config->toArray();
        $this->assertIsArray($array);
    }
}
