<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\CreditManagement\Models;

use Buckaroo\PaymentMethods\CreditManagement\Models\PaymentPlan;
use Tests\TestCase;

class PaymentPlanTest extends TestCase
{
    /** @test */
    public function it_sets_included_invoice_key(): void
    {
        $paymentPlan = new PaymentPlan(['includedInvoiceKey' => 'KEY-123']);

        $this->assertSame('KEY-123', $paymentPlan->includedInvoiceKey);
    }

    /** @test */
    public function it_sets_dossier_number(): void
    {
        $paymentPlan = new PaymentPlan(['dossierNumber' => 'DOSSIER-001']);

        $this->assertSame('DOSSIER-001', $paymentPlan->dossierNumber);
    }

    /** @test */
    public function it_sets_installment_count(): void
    {
        $paymentPlan = new PaymentPlan(['installmentCount' => 12]);

        $this->assertSame(12, $paymentPlan->installmentCount);
    }

    /** @test */
    public function it_sets_installment_amount(): void
    {
        $paymentPlan = new PaymentPlan(['installmentAmount' => 50.00]);

        $this->assertSame(50.00, $paymentPlan->installmentAmount);
    }

    /** @test */
    public function it_sets_initial_amount(): void
    {
        $paymentPlan = new PaymentPlan(['initialAmount' => 100.00]);

        $this->assertSame(100.00, $paymentPlan->initialAmount);
    }

    /** @test */
    public function it_sets_start_date(): void
    {
        $paymentPlan = new PaymentPlan(['startDate' => '2026-04-01']);

        $this->assertSame('2026-04-01', $paymentPlan->startDate);
    }

    /** @test */
    public function it_sets_interval(): void
    {
        $paymentPlan = new PaymentPlan(['interval' => 'monthly']);

        $this->assertSame('monthly', $paymentPlan->interval);
    }

    /** @test */
    public function it_sets_payment_plan_cost_amount(): void
    {
        $paymentPlan = new PaymentPlan(['paymentPlanCostAmount' => 25.00]);

        $this->assertSame(25.00, $paymentPlan->paymentPlanCostAmount);
    }

    /** @test */
    public function it_sets_payment_plan_cost_amount_vat(): void
    {
        $paymentPlan = new PaymentPlan(['paymentPlanCostAmountVat' => 5.25]);

        $this->assertSame(5.25, $paymentPlan->paymentPlanCostAmountVat);
    }

    /** @test */
    public function it_sets_recipient_email(): void
    {
        $paymentPlan = new PaymentPlan(['recipientEmail' => 'customer@example.com']);

        $this->assertSame('customer@example.com', $paymentPlan->recipientEmail);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $paymentPlan = new PaymentPlan([
            'includedInvoiceKey' => 'INV-KEY-456',
            'dossierNumber' => 'DOS-456',
            'installmentCount' => 6,
            'installmentAmount' => 83.33,
            'initialAmount' => 50.00,
            'startDate' => '2026-05-01',
            'interval' => 'weekly',
            'paymentPlanCostAmount' => 15.00,
            'paymentPlanCostAmountVat' => 3.15,
            'recipientEmail' => 'test@example.com',
        ]);

        $this->assertSame('INV-KEY-456', $paymentPlan->includedInvoiceKey);
        $this->assertSame('DOS-456', $paymentPlan->dossierNumber);
        $this->assertSame(6, $paymentPlan->installmentCount);
        $this->assertSame(83.33, $paymentPlan->installmentAmount);
        $this->assertSame(50.00, $paymentPlan->initialAmount);
        $this->assertSame('2026-05-01', $paymentPlan->startDate);
        $this->assertSame('weekly', $paymentPlan->interval);
        $this->assertSame(15.00, $paymentPlan->paymentPlanCostAmount);
        $this->assertSame(3.15, $paymentPlan->paymentPlanCostAmountVat);
        $this->assertSame('test@example.com', $paymentPlan->recipientEmail);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $paymentPlan = new PaymentPlan([
            'installmentCount' => 3,
            'installmentAmount' => 100.00,
        ]);

        $array = $paymentPlan->toArray();

        $this->assertIsArray($array);
        $this->assertSame(3, $array['installmentCount']);
        $this->assertSame(100.00, $array['installmentAmount']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $paymentPlan = new PaymentPlan([]);

        $array = $paymentPlan->toArray();
        $this->assertIsArray($array);
    }
}
