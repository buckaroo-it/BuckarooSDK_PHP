<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\CreditManagement\Models;

use Buckaroo\PaymentMethods\CreditManagement\Models\Debtor;
use Buckaroo\PaymentMethods\CreditManagement\Models\Invoice;
use Tests\TestCase;

class DebtorTest extends TestCase
{
    /** @test */
    public function it_extends_invoice_model(): void
    {
        $debtor = new Debtor([]);

        $this->assertInstanceOf(Invoice::class, $debtor);
    }

    /** @test */
    public function it_sets_address_unreachable(): void
    {
        $debtor = new Debtor(['addressUnreachable' => true]);

        $this->assertTrue($debtor->addressUnreachable);
    }

    /** @test */
    public function it_sets_email_unreachable(): void
    {
        $debtor = new Debtor(['emailUnreachable' => true]);

        $this->assertTrue($debtor->emailUnreachable);
    }

    /** @test */
    public function it_sets_mobile_unreachable(): void
    {
        $debtor = new Debtor(['mobileUnreachable' => true]);

        $this->assertTrue($debtor->mobileUnreachable);
    }

    /** @test */
    public function it_sets_landline_unreachable(): void
    {
        $debtor = new Debtor(['landlineUnreachable' => true]);

        $this->assertTrue($debtor->landlineUnreachable);
    }

    /** @test */
    public function it_sets_fax_unreachable(): void
    {
        $debtor = new Debtor(['faxUnreachable' => true]);

        $this->assertTrue($debtor->faxUnreachable);
    }

    /** @test */
    public function it_sets_all_unreachable_properties_to_false(): void
    {
        $debtor = new Debtor([
            'addressUnreachable' => false,
            'emailUnreachable' => false,
            'mobileUnreachable' => false,
            'landlineUnreachable' => false,
            'faxUnreachable' => false,
        ]);

        $this->assertFalse($debtor->addressUnreachable);
        $this->assertFalse($debtor->emailUnreachable);
        $this->assertFalse($debtor->mobileUnreachable);
        $this->assertFalse($debtor->landlineUnreachable);
        $this->assertFalse($debtor->faxUnreachable);
    }

    /** @test */
    public function it_sets_all_unreachable_properties_to_true(): void
    {
        $debtor = new Debtor([
            'addressUnreachable' => true,
            'emailUnreachable' => true,
            'mobileUnreachable' => true,
            'landlineUnreachable' => true,
            'faxUnreachable' => true,
        ]);

        $this->assertTrue($debtor->addressUnreachable);
        $this->assertTrue($debtor->emailUnreachable);
        $this->assertTrue($debtor->mobileUnreachable);
        $this->assertTrue($debtor->landlineUnreachable);
        $this->assertTrue($debtor->faxUnreachable);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $debtor = new Debtor([
            'addressUnreachable' => true,
            'emailUnreachable' => false,
        ]);

        $array = $debtor->toArray();

        $this->assertIsArray($array);
        $this->assertTrue($array['addressUnreachable']);
        $this->assertFalse($array['emailUnreachable']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $debtor = new Debtor([]);

        $array = $debtor->toArray();
        $this->assertIsArray($array);
    }
}
