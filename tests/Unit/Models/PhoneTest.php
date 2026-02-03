<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Buckaroo\Models\Phone;
use Tests\TestCase;

class PhoneTest extends TestCase
{
    public function test_initializes_all_phone_properties_from_constructor(): void
    {
        $phone = new Phone([
            'landLine' => '+31 20 1234567',
            'mobile' => '+31 6 12345678',
            'phone' => '0201234567',
            'fax' => '+31 20 7654321',
        ]);

        $this->assertSame('+31 20 1234567', $phone->landLine);
        $this->assertSame('+31 6 12345678', $phone->mobile);
        $this->assertSame('0201234567', $phone->phone);
        $this->assertSame('+31 20 7654321', $phone->fax);
    }

    public function test_handles_partial_initialization(): void
    {
        $phone = new Phone([
            'mobile' => '+31612345678',
            'phone' => '020-1234567',
        ]);

        $this->assertSame('+31612345678', $phone->mobile);
        $this->assertSame('020-1234567', $phone->phone);
    }

    public function test_to_array_preserves_type_integrity(): void
    {
        $phone = new Phone([
            'landLine' => '+31201234567',
            'mobile' => '+31612345678',
            'phone' => '0201234567',
            'fax' => '+31207654321',
        ]);

        $array = $phone->toArray();

        $this->assertIsString($array['landLine']);
        $this->assertIsString($array['mobile']);
        $this->assertIsString($array['phone']);
        $this->assertIsString($array['fax']);

        $this->assertSame('+31201234567', $array['landLine']);
        $this->assertSame('+31612345678', $array['mobile']);
        $this->assertSame('0201234567', $array['phone']);
        $this->assertSame('+31207654321', $array['fax']);
    }

    public function test_handles_international_formats_with_special_characters(): void
    {
        $formats = [
            'landLine' => '+1 (555) 123-4567',
            'mobile' => '+44 7700 900123',
            'phone' => '+49 (0)30 123456',
            'fax' => '+33 1 42 86 82 00',
        ];

        $phone = new Phone($formats);

        $this->assertSame('+1 (555) 123-4567', $phone->landLine);
        $this->assertSame('+44 7700 900123', $phone->mobile);
        $this->assertSame('+49 (0)30 123456', $phone->phone);
        $this->assertSame('+33 1 42 86 82 00', $phone->fax);

        $array = $phone->toArray();
        $this->assertSame('+1 (555) 123-4567', $array['landLine']);
        $this->assertSame('+44 7700 900123', $array['mobile']);
        $this->assertSame('+49 (0)30 123456', $array['phone']);
        $this->assertSame('+33 1 42 86 82 00', $array['fax']);
    }

    public function test_handles_multiple_phone_types_simultaneously(): void
    {
        $phone = new Phone([
            'landLine' => '020-1234567',
            'mobile' => '06-12345678',
            'phone' => '+31 20 1234567',
            'fax' => '020-7654321',
        ]);

        $this->assertSame('020-1234567', $phone->landLine);
        $this->assertSame('06-12345678', $phone->mobile);
        $this->assertSame('+31 20 1234567', $phone->phone);
        $this->assertSame('020-7654321', $phone->fax);

        $array = $phone->toArray();
        $this->assertCount(4, $array);
        $this->assertArrayHasKey('landLine', $array);
        $this->assertArrayHasKey('mobile', $array);
        $this->assertArrayHasKey('phone', $array);
        $this->assertArrayHasKey('fax', $array);
    }

    public function test_handles_empty_strings_and_numeric_only_values(): void
    {
        $phone = new Phone([
            'landLine' => '',
            'mobile' => '0612345678',
            'phone' => '1234567890',
            'fax' => '',
        ]);

        $this->assertSame('', $phone->landLine);
        $this->assertSame('0612345678', $phone->mobile);
        $this->assertSame('1234567890', $phone->phone);
        $this->assertSame('', $phone->fax);

        $array = $phone->toArray();
        $this->assertSame('', $array['landLine']);
        $this->assertSame('0612345678', $array['mobile']);
        $this->assertSame('1234567890', $array['phone']);
        $this->assertSame('', $array['fax']);
    }

    public function test_handles_extremely_long_phone_numbers(): void
    {
        $longInternational = '+1-555-123-4567 ext. 12345';
        $longMobile = '+86 138 0013 8000';
        $longFax = '+81 (0)3-1234-5678';

        $phone = new Phone([
            'landLine' => $longInternational,
            'mobile' => $longMobile,
            'fax' => $longFax,
        ]);

        $this->assertSame($longInternational, $phone->landLine);
        $this->assertSame($longMobile, $phone->mobile);
        $this->assertSame($longFax, $phone->fax);

        $array = $phone->toArray();
        $this->assertSame($longInternational, $array['landLine']);
        $this->assertSame($longMobile, $array['mobile']);
        $this->assertSame($longFax, $array['fax']);
    }
}
