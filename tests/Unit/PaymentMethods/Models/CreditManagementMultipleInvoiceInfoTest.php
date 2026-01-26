<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Models;

use Buckaroo\PaymentMethods\CreditManagement\Models\Invoice;
use Buckaroo\PaymentMethods\CreditManagement\Models\MultipleInvoiceInfo;
use Tests\TestCase;

class CreditManagementMultipleInvoiceInfoTest extends TestCase
{
    /** @test */
    public function it_sets_invoices_from_array(): void
    {
        $multipleInvoiceInfo = new MultipleInvoiceInfo([
            'invoices' => [
                [
                    'invoiceNumber' => 'INV-001',
                    'invoiceAmount' => 100.00,
                ],
                [
                    'invoiceNumber' => 'INV-002',
                    'invoiceAmount' => 200.00,
                ],
            ],
        ]);

        $invoices = $multipleInvoiceInfo->invoices();

        $this->assertIsArray($invoices);
        $this->assertCount(2, $invoices);
        $this->assertInstanceOf(Invoice::class, $invoices[0]);
        $this->assertInstanceOf(Invoice::class, $invoices[1]);
    }

    /** @test */
    public function it_returns_empty_invoices_array_without_parameter(): void
    {
        $multipleInvoiceInfo = new MultipleInvoiceInfo([]);

        $invoices = $multipleInvoiceInfo->invoices();

        $this->assertIsArray($invoices);
        $this->assertEmpty($invoices);
    }
}
