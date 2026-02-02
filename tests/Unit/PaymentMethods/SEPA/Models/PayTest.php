<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\SEPA\Models;

use Buckaroo\PaymentMethods\SEPA\Models\Pay;
use Buckaroo\PaymentMethods\SEPA\Service\ParameterKeys\CustomerAdapter;
use Tests\TestCase;

class PayTest extends TestCase
{
    /** @test */
    public function it_sets_bic(): void
    {
        $pay = new Pay(['bic' => 'ABNANL2A']);

        $this->assertSame('ABNANL2A', $pay->bic);
    }

    /** @test */
    public function it_sets_iban(): void
    {
        $pay = new Pay(['iban' => 'NL91ABNA0417164300']);

        $this->assertSame('NL91ABNA0417164300', $pay->iban);
    }

    /** @test */
    public function it_sets_collectdate(): void
    {
        $pay = new Pay(['collectdate' => '2026-03-15']);

        $this->assertSame('2026-03-15', $pay->collectdate);
    }

    /** @test */
    public function it_sets_mandate_reference(): void
    {
        $pay = new Pay(['mandateReference' => 'MANDATE-001']);

        $this->assertSame('MANDATE-001', $pay->mandateReference);
    }

    /** @test */
    public function it_sets_mandate_date(): void
    {
        $pay = new Pay(['mandateDate' => '2026-01-01']);

        $this->assertSame('2026-01-01', $pay->mandateDate);
    }

    /** @test */
    public function it_sets_customer_from_array(): void
    {
        $pay = new Pay([
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $customer = $pay->customer();

        $this->assertInstanceOf(CustomerAdapter::class, $customer);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $pay = new Pay([
            'bic' => 'INGBNL2A',
            'iban' => 'NL20INGB0001234567',
            'collectdate' => '2026-04-01',
            'mandateReference' => 'MANDATE-999',
            'mandateDate' => '2026-02-15',
        ]);

        $this->assertSame('INGBNL2A', $pay->bic);
        $this->assertSame('NL20INGB0001234567', $pay->iban);
        $this->assertSame('2026-04-01', $pay->collectdate);
        $this->assertSame('MANDATE-999', $pay->mandateReference);
        $this->assertSame('2026-02-15', $pay->mandateDate);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $pay = new Pay([
            'bic' => 'RABONL2U',
            'iban' => 'NL91RABO0315273637',
        ]);

        $array = $pay->toArray();

        $this->assertIsArray($array);
        $this->assertSame('RABONL2U', $array['bic']);
        $this->assertSame('NL91RABO0315273637', $array['iban']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }
}
