<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\AfterpayDigiAccept;

use Buckaroo\Models\Company;
use Buckaroo\PaymentMethods\Afterpay\Models\Person;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Models\Recipient;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys\RecipientAdapter;
use Tests\TestCase;

class RecipientAdapterTest extends TestCase
{
    /** @test */
    public function it_returns_prefixed_key_for_standard_properties(): void
    {
        $person = new Person(['firstName' => 'John', 'lastName' => 'Doe']);
        $adapter = new RecipientAdapter('Billing', $person);

        $key = $adapter->serviceParameterKeyOf('firstName');

        $this->assertSame('BillingFirstName', $key);
    }

    /** @test */
    public function it_returns_unprefixed_key_for_company_name(): void
    {
        $company = new Company(['companyName' => 'ACME Inc.']);
        $adapter = new RecipientAdapter('Billing', $company);

        $key = $adapter->serviceParameterKeyOf('companyName');

        $this->assertSame('CompanyName', $key);
    }

    /** @test */
    public function it_returns_mapped_key_for_chamber_of_commerce(): void
    {
        $company = new Company(['chamberOfCommerce' => '12345678']);
        $adapter = new RecipientAdapter('Billing', $company);

        $key = $adapter->serviceParameterKeyOf('chamberOfCommerce');

        $this->assertSame('CompanyCOCRegistration', $key);
    }

    /** @test */
    public function it_returns_unprefixed_key_for_vat_number(): void
    {
        $company = new Company(['vatNumber' => 'NL123456789B01']);
        $adapter = new RecipientAdapter('Billing', $company);

        $key = $adapter->serviceParameterKeyOf('vatNumber');

        $this->assertSame('VatNumber', $key);
    }

    /** @test */
    public function it_returns_mapped_and_prefixed_key_for_culture(): void
    {
        $person = new Person(['culture' => 'nl-NL']);
        $adapter = new RecipientAdapter('Shipping', $person);

        $key = $adapter->serviceParameterKeyOf('culture');

        $this->assertSame('ShippingLanguage', $key);
    }

    /** @test */
    public function it_works_with_recipient_model_for_person(): void
    {
        $recipient = new Recipient('Billing', [
            'recipient' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $this->assertInstanceOf(RecipientAdapter::class, $recipient->recipient());
    }

    /** @test */
    public function it_works_with_recipient_model_for_company(): void
    {
        $recipient = new Recipient('Billing', [
            'recipient' => [
                'companyName' => 'ACME Inc.',
                'chamberOfCommerce' => '12345678',
            ],
        ]);

        $this->assertInstanceOf(RecipientAdapter::class, $recipient->recipient());
    }
}
