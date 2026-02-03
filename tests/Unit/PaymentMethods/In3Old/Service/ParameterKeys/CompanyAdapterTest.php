<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\In3Old\Service\ParameterKeys;

use Buckaroo\Models\Company;
use Buckaroo\PaymentMethods\In3Old\Service\ParameterKeys\CompanyAdapter;
use Tests\TestCase;

class CompanyAdapterTest extends TestCase
{
    public function test_transforms_company_name_to_name(): void
    {
        $company = new Company(['companyName' => 'Acme Corp']);
        $adapter = new CompanyAdapter($company);

        $this->assertSame('Name', $adapter->serviceParameterKeyOf('companyName'));
    }

    public function test_uses_ucfirst_for_unmapped_properties(): void
    {
        $company = new Company(['vatNumber' => 'NL123456789B01']);
        $adapter = new CompanyAdapter($company);

        $this->assertSame('VatNumber', $adapter->serviceParameterKeyOf('vatNumber'));
        $this->assertSame('ChamberOfCommerce', $adapter->serviceParameterKeyOf('chamberOfCommerce'));
    }

    public function test_proxies_property_access_to_model(): void
    {
        $company = new Company([
            'companyName' => 'Test Company BV',
            'vatNumber' => 'NL987654321B01',
            'chamberOfCommerce' => '12345678',
        ]);

        $adapter = new CompanyAdapter($company);

        $this->assertSame('Test Company BV', $adapter->companyName);
        $this->assertSame('NL987654321B01', $adapter->vatNumber);
        $this->assertSame('12345678', $adapter->chamberOfCommerce);
    }

    public function test_delegates_to_array_to_model(): void
    {
        $company = new Company([
            'companyName' => 'Example Ltd',
            'vatNumber' => 'GB123456789',
        ]);

        $adapter = new CompanyAdapter($company);
        $array = $adapter->toArray();

        $this->assertIsArray($array);
        $this->assertSame('Example Ltd', $array['companyName']);
        $this->assertSame('GB123456789', $array['vatNumber']);
    }

    public function test_handles_various_company_name_formats(): void
    {
        $names = [
            'Simple Inc',
            'Company B.V.',
            'Firm & Partners',
            'Corp-123',
            'Multi Word Company Name Ltd.',
        ];

        foreach ($names as $name) {
            $company = new Company(['companyName' => $name]);
            $adapter = new CompanyAdapter($company);

            $this->assertSame($name, $adapter->companyName);
            $this->assertSame('Name', $adapter->serviceParameterKeyOf('companyName'));
        }
    }

    public function test_company_extends_person(): void
    {
        $company = new Company([
            'companyName' => 'Business Corp',
            'firstName' => 'John',
            'lastName' => 'Doe',
        ]);

        $adapter = new CompanyAdapter($company);

        $this->assertSame('Business Corp', $adapter->companyName);
        $this->assertSame('John', $adapter->firstName);
        $this->assertSame('Doe', $adapter->lastName);
    }
}
