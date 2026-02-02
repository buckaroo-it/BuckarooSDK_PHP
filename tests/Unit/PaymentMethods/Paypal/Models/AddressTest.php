<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Paypal\Models;

use Buckaroo\Models\Address as BaseAddress;
use Buckaroo\PaymentMethods\Paypal\Models\Address;
use Tests\TestCase;

class AddressTest extends TestCase
{
    /** @test */
    public function it_extends_base_address_model(): void
    {
        $address = new Address([]);

        $this->assertInstanceOf(BaseAddress::class, $address);
    }

    /** @test */
    public function it_sets_street2(): void
    {
        $address = new Address(['street2' => 'Apartment 4B']);

        $this->assertSame('Apartment 4B', $address->street2);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $address = new Address(['street2' => 'Suite 100']);

        $array = $address->toArray();

        $this->assertIsArray($array);
        $this->assertSame('Suite 100', $array['street2']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $address = new Address([]);

        $array = $address->toArray();
        $this->assertIsArray($array);
    }

    /**
     * @test
     * @dataProvider street2Provider
     */
    public function it_handles_various_street2_values(string $street2): void
    {
        $address = new Address(['street2' => $street2]);

        $this->assertSame($street2, $address->street2);
    }

    public static function street2Provider(): array
    {
        return [
            ['Apartment 1'],
            ['Suite 200'],
            ['Floor 3'],
            ['Building B'],
            ['Unit 15A'],
        ];
    }
}
