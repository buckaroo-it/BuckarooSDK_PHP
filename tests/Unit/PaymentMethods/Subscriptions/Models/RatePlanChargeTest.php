<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Subscriptions\Models;

use Buckaroo\PaymentMethods\Subscriptions\Models\RatePlanCharge;
use Tests\TestCase;

class RatePlanChargeTest extends TestCase
{
    /** @test */
    public function it_sets_rate_plan_charge_guid(): void
    {
        $charge = new RatePlanCharge([
            'ratePlanChargeGuid' => 'GUID-12345-ABCDE',
        ]);

        $this->assertEquals('GUID-12345-ABCDE', $charge->ratePlanChargeGuid);
    }

    /** @test */
    public function it_sets_rate_plan_charge_code(): void
    {
        $charge = new RatePlanCharge([
            'ratePlanChargeCode' => 'CHARGE-CODE-001',
        ]);

        $this->assertEquals('CHARGE-CODE-001', $charge->ratePlanChargeCode);
    }

    /** @test */
    public function it_sets_rate_plan_charge_name(): void
    {
        $charge = new RatePlanCharge([
            'ratePlanChargeName' => 'Monthly Subscription',
        ]);

        $this->assertEquals('Monthly Subscription', $charge->ratePlanChargeName);
    }

    /** @test */
    public function it_sets_product_id(): void
    {
        $charge = new RatePlanCharge([
            'rateplanChargeProductId' => 'PROD-001',
        ]);

        $this->assertEquals('PROD-001', $charge->rateplanChargeProductId);
    }

    /** @test */
    public function it_sets_description(): void
    {
        $charge = new RatePlanCharge([
            'rateplanChargeDescription' => 'Premium subscription plan',
        ]);

        $this->assertEquals('Premium subscription plan', $charge->rateplanChargeDescription);
    }

    /** @test */
    public function it_sets_unit_of_measure(): void
    {
        $charge = new RatePlanCharge([
            'unitOfMeasure' => 'months',
        ]);

        $this->assertEquals('months', $charge->unitOfMeasure);
    }

    /** @test */
    public function it_sets_base_number_of_units(): void
    {
        $charge = new RatePlanCharge([
            'baseNumberOfUnits' => 12.0,
        ]);

        $this->assertEquals(12.0, $charge->baseNumberOfUnits);
    }

    /** @test */
    public function it_sets_partial_billing(): void
    {
        $charge = new RatePlanCharge([
            'partialBilling' => 'prorated',
        ]);

        $this->assertEquals('prorated', $charge->partialBilling);
    }

    /** @test */
    public function it_sets_price_per_unit(): void
    {
        $charge = new RatePlanCharge([
            'pricePerUnit' => 9.99,
        ]);

        $this->assertEquals(9.99, $charge->pricePerUnit);
    }

    /** @test */
    public function it_sets_price_includes_vat(): void
    {
        $charge = new RatePlanCharge([
            'priceIncludesVat' => true,
        ]);

        $this->assertTrue($charge->priceIncludesVat);
    }

    /** @test */
    public function it_sets_vat_percentage(): void
    {
        $charge = new RatePlanCharge([
            'vatPercentage' => 21.0,
        ]);

        $this->assertEquals(21.0, $charge->vatPercentage);
    }

    /** @test */
    public function it_sets_b2b_flag(): void
    {
        $charge = new RatePlanCharge([
            'b2B' => 'true',
        ]);

        $this->assertEquals('true', $charge->b2B);
    }

    /** @test */
    public function it_sets_rate_plan_charge_type(): void
    {
        $charge = new RatePlanCharge([
            'ratePlanChargeType' => 'recurring',
        ]);

        $this->assertEquals('recurring', $charge->ratePlanChargeType);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $charge = new RatePlanCharge([
            'ratePlanChargeGuid' => 'GUID-12345',
            'ratePlanChargeCode' => 'CODE-001',
            'ratePlanChargeName' => 'Premium Plan',
            'rateplanChargeProductId' => 'PROD-001',
            'rateplanChargeDescription' => 'Premium subscription',
            'unitOfMeasure' => 'months',
            'baseNumberOfUnits' => 1.0,
            'partialBilling' => 'full',
            'pricePerUnit' => 29.99,
            'priceIncludesVat' => true,
            'vatPercentage' => 21.0,
            'b2B' => 'false',
            'ratePlanChargeType' => 'recurring',
        ]);

        $this->assertEquals('GUID-12345', $charge->ratePlanChargeGuid);
        $this->assertEquals('CODE-001', $charge->ratePlanChargeCode);
        $this->assertEquals('Premium Plan', $charge->ratePlanChargeName);
        $this->assertEquals('PROD-001', $charge->rateplanChargeProductId);
        $this->assertEquals('Premium subscription', $charge->rateplanChargeDescription);
        $this->assertEquals('months', $charge->unitOfMeasure);
        $this->assertEquals(1.0, $charge->baseNumberOfUnits);
        $this->assertEquals('full', $charge->partialBilling);
        $this->assertEquals(29.99, $charge->pricePerUnit);
        $this->assertTrue($charge->priceIncludesVat);
        $this->assertEquals(21.0, $charge->vatPercentage);
        $this->assertEquals('false', $charge->b2B);
        $this->assertEquals('recurring', $charge->ratePlanChargeType);
    }
}
