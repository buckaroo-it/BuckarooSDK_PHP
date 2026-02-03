<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\BuckarooVoucher\Models;

use Buckaroo\PaymentMethods\BuckarooVoucher\Models\Create;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function it_sets_group_reference(): void
    {
        $create = new Create(['groupReference' => 'GROUP-REF-001']);

        $this->assertSame('GROUP-REF-001', $create->groupReference);
    }

    /** @test */
    public function it_sets_usage_type(): void
    {
        $create = new Create(['usageType' => 'single']);

        $this->assertSame('single', $create->usageType);
    }

    /** @test */
    public function it_sets_valid_from(): void
    {
        $create = new Create(['validFrom' => '2026-01-01']);

        $this->assertSame('2026-01-01', $create->validFrom);
    }

    /** @test */
    public function it_sets_valid_until(): void
    {
        $create = new Create(['validUntil' => '2026-12-31']);

        $this->assertSame('2026-12-31', $create->validUntil);
    }

    /** @test */
    public function it_sets_creation_balance(): void
    {
        $create = new Create(['creationBalance' => '100.00']);

        $this->assertSame('100.00', $create->creationBalance);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $create = new Create([
            'groupReference' => 'GROUP-123',
            'usageType' => 'multiple',
            'validFrom' => '2026-02-01',
            'validUntil' => '2027-02-01',
            'creationBalance' => '250.00',
        ]);

        $this->assertSame('GROUP-123', $create->groupReference);
        $this->assertSame('multiple', $create->usageType);
        $this->assertSame('2026-02-01', $create->validFrom);
        $this->assertSame('2027-02-01', $create->validUntil);
        $this->assertSame('250.00', $create->creationBalance);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $create = new Create([
            'groupReference' => 'REF-TEST',
            'usageType' => 'single',
        ]);

        $array = $create->toArray();

        $this->assertIsArray($array);
        $this->assertSame('REF-TEST', $array['groupReference']);
        $this->assertSame('single', $array['usageType']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $create = new Create([]);

        $array = $create->toArray();
        $this->assertIsArray($array);
    }
}
