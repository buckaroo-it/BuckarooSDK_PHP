<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\CreditManagement\Models;

use Buckaroo\PaymentMethods\CreditManagement\Models\CreditNote;
use Tests\TestCase;

class CreditNoteTest extends TestCase
{
    /** @test */
    public function it_sets_original_invoice_number(): void
    {
        $creditNote = new CreditNote(['originalInvoiceNumber' => 'INV-2026-001']);

        $this->assertSame('INV-2026-001', $creditNote->originalInvoiceNumber);
    }

    /** @test */
    public function it_sets_invoice_date(): void
    {
        $creditNote = new CreditNote(['invoiceDate' => '2026-02-01']);

        $this->assertSame('2026-02-01', $creditNote->invoiceDate);
    }

    /** @test */
    public function it_sets_invoice_amount(): void
    {
        $creditNote = new CreditNote(['invoiceAmount' => '150.00']);

        $this->assertSame('150.00', $creditNote->invoiceAmount);
    }

    /** @test */
    public function it_sets_invoice_amount_vat(): void
    {
        $creditNote = new CreditNote(['invoiceAmountVAT' => '31.50']);

        $this->assertSame('31.50', $creditNote->invoiceAmountVAT);
    }

    /** @test */
    public function it_sets_send_credit_note_message(): void
    {
        $creditNote = new CreditNote(['sendCreditNoteMessage' => 'true']);

        $this->assertSame('true', $creditNote->sendCreditNoteMessage);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $creditNote = new CreditNote([
            'originalInvoiceNumber' => 'INV-999',
            'invoiceDate' => '2026-03-15',
            'invoiceAmount' => '200.00',
            'invoiceAmountVAT' => '42.00',
            'sendCreditNoteMessage' => 'false',
        ]);

        $this->assertSame('INV-999', $creditNote->originalInvoiceNumber);
        $this->assertSame('2026-03-15', $creditNote->invoiceDate);
        $this->assertSame('200.00', $creditNote->invoiceAmount);
        $this->assertSame('42.00', $creditNote->invoiceAmountVAT);
        $this->assertSame('false', $creditNote->sendCreditNoteMessage);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $creditNote = new CreditNote([
            'originalInvoiceNumber' => 'INV-TEST',
            'invoiceAmount' => '99.99',
        ]);

        $array = $creditNote->toArray();

        $this->assertIsArray($array);
        $this->assertSame('INV-TEST', $array['originalInvoiceNumber']);
        $this->assertSame('99.99', $array['invoiceAmount']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $creditNote = new CreditNote([]);

        $array = $creditNote->toArray();
        $this->assertIsArray($array);
    }
}
