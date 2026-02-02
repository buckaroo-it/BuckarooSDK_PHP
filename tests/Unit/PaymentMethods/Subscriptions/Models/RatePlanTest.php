<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Subscriptions\Models;

use Buckaroo\PaymentMethods\Subscriptions\Models\RatePlan;
use Tests\TestCase;

class RatePlanTest extends TestCase
{
    /** @test */
    public function it_sets_rate_plan_guid(): void
    {
        $ratePlan = new RatePlan(['ratePlanGuid' => 'GUID-12345']);

        $this->assertSame('GUID-12345', $ratePlan->ratePlanGuid);
    }

    /** @test */
    public function it_sets_rate_plan_code(): void
    {
        $ratePlan = new RatePlan(['ratePlanCode' => 'PLAN-001']);

        $this->assertSame('PLAN-001', $ratePlan->ratePlanCode);
    }

    /** @test */
    public function it_sets_rate_plan_name(): void
    {
        $ratePlan = new RatePlan(['ratePlanName' => 'Premium Plan']);

        $this->assertSame('Premium Plan', $ratePlan->ratePlanName);
    }

    /** @test */
    public function it_sets_rate_plan_description(): void
    {
        $ratePlan = new RatePlan(['ratePlanDescription' => 'Monthly subscription plan']);

        $this->assertSame('Monthly subscription plan', $ratePlan->ratePlanDescription);
    }

    /** @test */
    public function it_sets_currency(): void
    {
        $ratePlan = new RatePlan(['currency' => 'EUR']);

        $this->assertSame('EUR', $ratePlan->currency);
    }

    /** @test */
    public function it_sets_billing_interval(): void
    {
        $ratePlan = new RatePlan(['billingInterval' => 'Monthly']);

        $this->assertSame('Monthly', $ratePlan->billingInterval);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $ratePlan = new RatePlan([
            'ratePlanGuid' => 'GUID-999',
            'ratePlanCode' => 'CODE-999',
            'ratePlanName' => 'Enterprise Plan',
            'ratePlanDescription' => 'Full-featured enterprise subscription',
            'currency' => 'USD',
            'billingInterval' => 'Yearly',
        ]);

        $this->assertSame('GUID-999', $ratePlan->ratePlanGuid);
        $this->assertSame('CODE-999', $ratePlan->ratePlanCode);
        $this->assertSame('Enterprise Plan', $ratePlan->ratePlanName);
        $this->assertSame('Full-featured enterprise subscription', $ratePlan->ratePlanDescription);
        $this->assertSame('USD', $ratePlan->currency);
        $this->assertSame('Yearly', $ratePlan->billingInterval);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $ratePlan = new RatePlan([
            'ratePlanCode' => 'PLAN-TEST',
            'ratePlanName' => 'Test Plan',
        ]);

        $array = $ratePlan->toArray();

        $this->assertIsArray($array);
        $this->assertSame('PLAN-TEST', $array['ratePlanCode']);
        $this->assertSame('Test Plan', $array['ratePlanName']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $ratePlan = new RatePlan([]);

        $array = $ratePlan->toArray();
        $this->assertIsArray($array);
    }
}
