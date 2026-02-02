<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\In3Old\Models;

use Buckaroo\PaymentMethods\In3Old\Models\Subtotal;
use Tests\TestCase;

class SubtotalTest extends TestCase
{
    /** @test */
    public function it_sets_name(): void
    {
        $subtotal = new Subtotal(['name' => 'Products']);

        $this->assertSame('Products', $subtotal->name);
    }

    /** @test */
    public function it_sets_value(): void
    {
        $subtotal = new Subtotal(['value' => 99.99]);

        $this->assertSame(99.99, $subtotal->value);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $subtotal = new Subtotal([
            'name' => 'Shipping',
            'value' => 5.95,
        ]);

        $this->assertSame('Shipping', $subtotal->name);
        $this->assertSame(5.95, $subtotal->value);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $subtotal = new Subtotal([
            'name' => 'Tax',
            'value' => 21.00,
        ]);

        $array = $subtotal->toArray();

        $this->assertIsArray($array);
        $this->assertSame('Tax', $array['name']);
        $this->assertSame(21.00, $array['value']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $subtotal = new Subtotal([]);

        $array = $subtotal->toArray();
        $this->assertIsArray($array);
    }

    /**
     * @test
     * @dataProvider subtotalTypesProvider
     */
    public function it_handles_various_subtotal_types(string $name, float $value): void
    {
        $subtotal = new Subtotal([
            'name' => $name,
            'value' => $value,
        ]);

        $this->assertSame($name, $subtotal->name);
        $this->assertSame($value, $subtotal->value);
    }

    public static function subtotalTypesProvider(): array
    {
        return [
            ['Products', 100.00],
            ['Shipping', 5.95],
            ['Tax', 21.00],
            ['Discount', -10.00],
            ['Service Fee', 2.50],
        ];
    }
}
