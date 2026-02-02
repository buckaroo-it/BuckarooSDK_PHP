<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\SEPA\Models;

use Buckaroo\PaymentMethods\SEPA\Models\ExtraInfo;
use Buckaroo\PaymentMethods\SEPA\Models\Pay;
use Buckaroo\PaymentMethods\SEPA\Service\ParameterKeys\AddressAdapter;
use Tests\TestCase;

class ExtraInfoTest extends TestCase
{
    /** @test */
    public function it_extends_pay_model(): void
    {
        $extraInfo = new ExtraInfo([]);

        $this->assertInstanceOf(Pay::class, $extraInfo);
    }

    /** @test */
    public function it_sets_customer_reference_party_name(): void
    {
        $extraInfo = new ExtraInfo(['customerReferencePartyName' => 'Test Company']);

        $this->assertSame('Test Company', $extraInfo->customerReferencePartyName);
    }

    /** @test */
    public function it_sets_customer_reference_party_code(): void
    {
        $extraInfo = new ExtraInfo(['customerReferencePartyCode' => 'CODE-123']);

        $this->assertSame('CODE-123', $extraInfo->customerReferencePartyCode);
    }

    /** @test */
    public function it_sets_customercode(): void
    {
        $extraInfo = new ExtraInfo(['customercode' => 'CUST-456']);

        $this->assertSame('CUST-456', $extraInfo->customercode);
    }

    /** @test */
    public function it_sets_contract_id(): void
    {
        $extraInfo = new ExtraInfo(['contractID' => 'CONTRACT-789']);

        $this->assertSame('CONTRACT-789', $extraInfo->contractID);
    }

    /** @test */
    public function it_sets_address_from_array(): void
    {
        $extraInfo = new ExtraInfo([
            'address' => [
                'street' => 'Main Street',
                'houseNumber' => '123',
            ],
        ]);

        $address = $extraInfo->address();

        $this->assertInstanceOf(AddressAdapter::class, $address);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $extraInfo = new ExtraInfo([
            'customerReferencePartyName' => 'Company Inc',
            'customerReferencePartyCode' => 'REF-001',
            'customercode' => 'CUST-001',
            'contractID' => 'CONTRACT-001',
        ]);

        $this->assertSame('Company Inc', $extraInfo->customerReferencePartyName);
        $this->assertSame('REF-001', $extraInfo->customerReferencePartyCode);
        $this->assertSame('CUST-001', $extraInfo->customercode);
        $this->assertSame('CONTRACT-001', $extraInfo->contractID);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $extraInfo = new ExtraInfo([
            'customercode' => 'TEST-CODE',
            'contractID' => 'TEST-CONTRACT',
        ]);

        $array = $extraInfo->toArray();

        $this->assertIsArray($array);
        $this->assertSame('TEST-CODE', $array['customercode']);
        $this->assertSame('TEST-CONTRACT', $array['contractID']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $extraInfo = new ExtraInfo([]);

        $array = $extraInfo->toArray();
        $this->assertIsArray($array);
    }
}
