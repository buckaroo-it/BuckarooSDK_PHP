<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Buckaroo\Models\Address;
use Tests\TestCase;

class AddressTest extends TestCase
{
    public function test_initializes_all_properties(): void
    {
        $address = new Address([
            'street' => 'Main Street',
            'houseNumber' => '123',
            'houseNumberAdditional' => 'A',
            'zipcode' => '1234 AB',
            'city' => 'Amsterdam',
            'state' => 'North Holland',
            'country' => 'NL',
        ]);

        $this->assertSame('Main Street', $address->street);
        $this->assertSame('123', $address->houseNumber);
        $this->assertSame('A', $address->houseNumberAdditional);
        $this->assertSame('1234 AB', $address->zipcode);
        $this->assertSame('Amsterdam', $address->city);
        $this->assertSame('North Holland', $address->state);
        $this->assertSame('NL', $address->country);
    }

    public function test_handles_partial_initialization(): void
    {
        $minimalAddress = new Address([
            'street' => 'Baker Street',
            'houseNumber' => '221',
            'city' => 'London',
            'country' => 'GB',
        ]);

        $this->assertSame('Baker Street', $minimalAddress->street);
        $this->assertSame('221', $minimalAddress->houseNumber);
        $this->assertSame('London', $minimalAddress->city);
        $this->assertSame('GB', $minimalAddress->country);
        $this->assertNull($minimalAddress->zipcode);

        $fullAddress = new Address([
            'street' => 'Kalverstraat',
            'houseNumber' => '92',
            'houseNumberAdditional' => 'III',
            'zipcode' => '1012 PH',
            'city' => 'Amsterdam',
            'state' => 'Noord-Holland',
            'country' => 'NL',
        ]);

        $this->assertSame('Kalverstraat', $fullAddress->street);
        $this->assertSame('92', $fullAddress->houseNumber);
        $this->assertSame('III', $fullAddress->houseNumberAdditional);
        $this->assertSame('1012 PH', $fullAddress->zipcode);
        $this->assertSame('Amsterdam', $fullAddress->city);
        $this->assertSame('Noord-Holland', $fullAddress->state);
        $this->assertSame('NL', $fullAddress->country);
    }

    public function test_to_array_preserves_all_values(): void
    {
        $address = new Address([
            'street' => 'Herengracht',
            'houseNumber' => '501',
            'houseNumberAdditional' => 'B',
            'zipcode' => '1017 BV',
            'city' => 'Amsterdam',
            'state' => 'Noord-Holland',
            'country' => 'NL',
        ]);

        $array = $address->toArray();

        $this->assertIsArray($array);
        $this->assertSame('Herengracht', $array['street']);
        $this->assertSame('501', $array['houseNumber']);
        $this->assertSame('B', $array['houseNumberAdditional']);
        $this->assertSame('1017 BV', $array['zipcode']);
        $this->assertSame('Amsterdam', $array['city']);
        $this->assertSame('Noord-Holland', $array['state']);
        $this->assertSame('NL', $array['country']);
    }

    public function test_handles_international_address_formats(): void
    {
        $ukAddress = new Address([
            'street' => 'High Street',
            'houseNumber' => '10',
            'zipcode' => 'SW1A 1AA',
            'city' => 'London',
            'country' => 'GB',
        ]);

        $this->assertSame('High Street', $ukAddress->street);
        $this->assertSame('SW1A 1AA', $ukAddress->zipcode);
        $this->assertSame('London', $ukAddress->city);
        $this->assertSame('GB', $ukAddress->country);

        $germanAddress = new Address([
            'street' => 'Hauptstraße',
            'houseNumber' => '15',
            'zipcode' => '10115',
            'city' => 'Berlin',
            'state' => 'Berlin',
            'country' => 'DE',
        ]);

        $this->assertSame('Hauptstraße', $germanAddress->street);
        $this->assertSame('10115', $germanAddress->zipcode);
        $this->assertSame('Berlin', $germanAddress->city);
        $this->assertSame('Berlin', $germanAddress->state);

        $usAddress = new Address([
            'street' => 'Fifth Avenue',
            'houseNumber' => '350',
            'zipcode' => '10118',
            'city' => 'New York',
            'state' => 'NY',
            'country' => 'US',
        ]);

        $this->assertSame('Fifth Avenue', $usAddress->street);
        $this->assertSame('NY', $usAddress->state);
        $this->assertSame('US', $usAddress->country);

        $dutchAddress = new Address([
            'street' => 'Keizersgracht',
            'houseNumber' => '555',
            'houseNumberAdditional' => 'H',
            'zipcode' => '1017 DR',
            'city' => 'Amsterdam',
            'country' => 'NL',
        ]);

        $this->assertSame('Keizersgracht', $dutchAddress->street);
        $this->assertSame('H', $dutchAddress->houseNumberAdditional);
        $this->assertSame('1017 DR', $dutchAddress->zipcode);
    }

    public function test_handles_unicode_addresses(): void
    {
        $arabicAddress = new Address([
            'street' => 'شارع الملك فهد',
            'houseNumber' => '123',
            'city' => 'الرياض',
            'country' => 'SA',
        ]);

        $this->assertSame('شارع الملك فهد', $arabicAddress->street);
        $this->assertSame('الرياض', $arabicAddress->city);

        $chineseAddress = new Address([
            'street' => '南京东路',
            'houseNumber' => '100',
            'city' => '上海',
            'country' => 'CN',
        ]);

        $this->assertSame('南京东路', $chineseAddress->street);
        $this->assertSame('上海', $chineseAddress->city);

        $cyrillicAddress = new Address([
            'street' => 'Тверская улица',
            'houseNumber' => '7',
            'zipcode' => '125009',
            'city' => 'Москва',
            'country' => 'RU',
        ]);

        $this->assertSame('Тверская улица', $cyrillicAddress->street);
        $this->assertSame('Москва', $cyrillicAddress->city);

        $greekAddress = new Address([
            'street' => 'Πανεπιστημίου',
            'houseNumber' => '34',
            'city' => 'Αθήνα',
            'country' => 'GR',
        ]);

        $this->assertSame('Πανεπιστημίου', $greekAddress->street);
        $this->assertSame('Αθήνα', $greekAddress->city);

        $array = $arabicAddress->toArray();
        $this->assertSame('شارع الملك فهد', $array['street']);
        $this->assertSame('الرياض', $array['city']);
    }

    public function test_house_number_additional_variations(): void
    {
        $variations = ['A', 'B2', 'III', 'bis', '1/2', 'Apt 5', 'Unit 12', 'rear', 'top floor'];

        foreach ($variations as $suffix) {
            $address = new Address([
                'street' => 'Test Street',
                'houseNumber' => '100',
                'houseNumberAdditional' => $suffix,
                'city' => 'Test City',
                'country' => 'NL',
            ]);

            $this->assertSame($suffix, $address->houseNumberAdditional);
            $this->assertSame($suffix, $address->toArray()['houseNumberAdditional']);
        }
    }
}
