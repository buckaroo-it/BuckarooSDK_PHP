<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Afterpay\Models;

use Buckaroo\Models\Person as BasePerson;
use Buckaroo\PaymentMethods\Afterpay\Models\Person;
use Tests\TestCase;

class PersonTest extends TestCase
{
    /** @test */
    public function it_extends_base_person_model(): void
    {
        $person = new Person([]);

        $this->assertInstanceOf(BasePerson::class, $person);
    }

    /** @test */
    public function it_sets_customer_number(): void
    {
        $person = new Person(['customerNumber' => 'CUST-12345']);

        $this->assertSame('CUST-12345', $person->customerNumber);
    }

    /** @test */
    public function it_sets_identification_number(): void
    {
        $person = new Person(['identificationNumber' => 'ID-67890']);

        $this->assertSame('ID-67890', $person->identificationNumber);
    }

    /** @test */
    public function it_sets_conversation_language(): void
    {
        $person = new Person(['conversationLanguage' => 'NL']);

        $this->assertSame('NL', $person->conversationLanguage);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $person = new Person([
            'customerNumber' => 'CUST-999',
            'identificationNumber' => 'ID-999',
            'conversationLanguage' => 'EN',
        ]);

        $this->assertSame('CUST-999', $person->customerNumber);
        $this->assertSame('ID-999', $person->identificationNumber);
        $this->assertSame('EN', $person->conversationLanguage);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $person = new Person([
            'customerNumber' => 'CUST-TEST',
            'conversationLanguage' => 'DE',
        ]);

        $array = $person->toArray();

        $this->assertIsArray($array);
        $this->assertSame('CUST-TEST', $array['customerNumber']);
        $this->assertSame('DE', $array['conversationLanguage']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $person = new Person([]);

        $array = $person->toArray();
        $this->assertIsArray($array);
    }
}
