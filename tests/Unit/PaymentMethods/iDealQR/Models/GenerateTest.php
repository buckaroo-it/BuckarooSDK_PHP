<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\iDealQR\Models;

use Buckaroo\PaymentMethods\iDealQR\Models\Generate;
use Tests\TestCase;

class GenerateTest extends TestCase
{
    /** @test */
    public function it_sets_amount(): void
    {
        $generate = new Generate(['amount' => 50.00]);

        $this->assertSame(50.00, $generate->amount);
    }

    /** @test */
    public function it_sets_amount_is_changeable(): void
    {
        $generate = new Generate(['amountIsChangeable' => true]);

        $this->assertTrue($generate->amountIsChangeable);
    }

    /** @test */
    public function it_sets_min_amount(): void
    {
        $generate = new Generate(['minAmount' => 1.00]);

        $this->assertSame(1.00, $generate->minAmount);
    }

    /** @test */
    public function it_sets_max_amount(): void
    {
        $generate = new Generate(['maxAmount' => 100.00]);

        $this->assertSame(100.00, $generate->maxAmount);
    }

    /** @test */
    public function it_sets_image_size(): void
    {
        $generate = new Generate(['imageSize' => 500]);

        $this->assertSame(500, $generate->imageSize);
    }

    /** @test */
    public function it_sets_purchase_id(): void
    {
        $generate = new Generate(['purchaseId' => 'PURCHASE-QR-001']);

        $this->assertSame('PURCHASE-QR-001', $generate->purchaseId);
    }

    /** @test */
    public function it_sets_description(): void
    {
        $generate = new Generate(['description' => 'QR Payment']);

        $this->assertSame('QR Payment', $generate->description);
    }

    /** @test */
    public function it_sets_is_one_off(): void
    {
        $generate = new Generate(['isOneOff' => true]);

        $this->assertTrue($generate->isOneOff);
    }

    /** @test */
    public function it_sets_expiration(): void
    {
        $generate = new Generate(['expiration' => '2026-12-31']);

        $this->assertSame('2026-12-31', $generate->expiration);
    }

    /** @test */
    public function it_sets_is_processing(): void
    {
        $generate = new Generate(['isProcessing' => false]);

        $this->assertFalse($generate->isProcessing);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $generate = new Generate([
            'amount' => 75.00,
            'amountIsChangeable' => true,
            'minAmount' => 5.00,
            'maxAmount' => 500.00,
            'imageSize' => 300,
            'purchaseId' => 'PUR-123',
            'description' => 'Test QR',
            'isOneOff' => true,
            'expiration' => '2026-06-30',
            'isProcessing' => true,
        ]);

        $this->assertSame(75.00, $generate->amount);
        $this->assertTrue($generate->amountIsChangeable);
        $this->assertSame(5.00, $generate->minAmount);
        $this->assertSame(500.00, $generate->maxAmount);
        $this->assertSame(300, $generate->imageSize);
        $this->assertSame('PUR-123', $generate->purchaseId);
        $this->assertSame('Test QR', $generate->description);
        $this->assertTrue($generate->isOneOff);
        $this->assertSame('2026-06-30', $generate->expiration);
        $this->assertTrue($generate->isProcessing);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $generate = new Generate([
            'minAmount' => 10.00,
            'maxAmount' => 200.00,
        ]);

        $array = $generate->toArray();

        $this->assertIsArray($array);
        $this->assertSame(10.00, $array['minAmount']);
        $this->assertSame(200.00, $array['maxAmount']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $generate = new Generate([]);

        $array = $generate->toArray();
        $this->assertIsArray($array);
    }
}
