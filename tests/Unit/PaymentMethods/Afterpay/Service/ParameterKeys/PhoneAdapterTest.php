<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Afterpay\Service\ParameterKeys;

use Buckaroo\Models\Phone;
use Buckaroo\PaymentMethods\Afterpay\Service\ParameterKeys\PhoneAdapter;
use Tests\TestCase;

class PhoneAdapterTest extends TestCase
{
    public function test_transforms_landline_to_phone(): void
    {
        $phone = new Phone(['landLine' => '0201234567']);
        $adapter = new PhoneAdapter($phone);

        $this->assertSame('Phone', $adapter->serviceParameterKeyOf('landLine'));
    }

    public function test_transforms_mobile_to_mobile_phone(): void
    {
        $phone = new Phone(['mobile' => '0612345678']);
        $adapter = new PhoneAdapter($phone);

        $this->assertSame('MobilePhone', $adapter->serviceParameterKeyOf('mobile'));
    }

    public function test_uses_ucfirst_for_unmapped_properties(): void
    {
        $phone = new Phone(['phone' => '0201234567']);
        $adapter = new PhoneAdapter($phone);

        $this->assertSame('Phone', $adapter->serviceParameterKeyOf('phone'));
        $this->assertSame('Fax', $adapter->serviceParameterKeyOf('fax'));
    }

    public function test_proxies_property_access_to_model(): void
    {
        $phone = new Phone([
            'landLine' => '0201234567',
            'mobile' => '0612345678',
            'phone' => '0301234567',
            'fax' => '0209876543',
        ]);

        $adapter = new PhoneAdapter($phone);

        $this->assertSame('0201234567', $adapter->landLine);
        $this->assertSame('0612345678', $adapter->mobile);
        $this->assertSame('0301234567', $adapter->phone);
        $this->assertSame('0209876543', $adapter->fax);
    }

    public function test_delegates_to_array_to_model(): void
    {
        $phone = new Phone([
            'landLine' => '0201111111',
            'mobile' => '0612222222',
        ]);

        $adapter = new PhoneAdapter($phone);
        $array = $adapter->toArray();

        $this->assertIsArray($array);
        $this->assertSame('0201111111', $array['landLine']);
        $this->assertSame('0612222222', $array['mobile']);
    }

    public function test_handles_international_phone_formats(): void
    {
        $testCases = [
            '+31201234567',
            '+441234567890',
            '+49301234567',
            '00316123456789',
        ];

        foreach ($testCases as $phoneNumber) {
            $phone = new Phone(['mobile' => $phoneNumber]);
            $adapter = new PhoneAdapter($phone);

            $this->assertSame($phoneNumber, $adapter->mobile);
            $this->assertSame('MobilePhone', $adapter->serviceParameterKeyOf('mobile'));
        }
    }

    public function test_all_key_mappings_are_correct(): void
    {
        $phone = new Phone([
            'landLine' => '0201234567',
            'mobile' => '0612345678',
        ]);

        $adapter = new PhoneAdapter($phone);

        $expectedMappings = [
            'landLine' => 'Phone',
            'mobile' => 'MobilePhone',
        ];

        foreach ($expectedMappings as $property => $expectedKey) {
            $this->assertSame($expectedKey, $adapter->serviceParameterKeyOf($property));
        }
    }
}
