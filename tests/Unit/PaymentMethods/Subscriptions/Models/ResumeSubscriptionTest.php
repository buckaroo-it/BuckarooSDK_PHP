<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Subscriptions\Models;

use Buckaroo\PaymentMethods\Subscriptions\Models\ResumeSubscription;
use Buckaroo\PaymentMethods\Subscriptions\Models\Subscription;
use Tests\TestCase;

class ResumeSubscriptionTest extends TestCase
{
    /** @test */
    public function it_extends_subscription_model(): void
    {
        $resume = new ResumeSubscription([]);

        $this->assertInstanceOf(Subscription::class, $resume);
    }

    /** @test */
    public function it_sets_resume_date(): void
    {
        $resume = new ResumeSubscription(['resumeDate' => '2026-03-15']);

        $this->assertSame('2026-03-15', $resume->resumeDate);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $resume = new ResumeSubscription(['resumeDate' => '2026-06-01']);

        $array = $resume->toArray();

        $this->assertIsArray($array);
        $this->assertSame('2026-06-01', $array['resumeDate']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $resume = new ResumeSubscription([]);

        $array = $resume->toArray();
        $this->assertIsArray($array);
    }

    /** @test */
    public function it_inherits_subscription_properties(): void
    {
        $resume = new ResumeSubscription([
            'resumeDate' => '2026-04-01',
        ]);

        $this->assertSame('2026-04-01', $resume->resumeDate);
    }
}
