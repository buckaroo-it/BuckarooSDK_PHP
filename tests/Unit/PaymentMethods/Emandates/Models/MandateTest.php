<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\Emandates\Models;

use Buckaroo\PaymentMethods\Emandates\Models\Mandate;
use Tests\TestCase;

class MandateTest extends TestCase
{
    /** @test */
    public function it_sets_debtorbankid_property(): void
    {
        $mandate = new Mandate(['debtorbankid' => 'BANK123']);

        $this->assertSame('BANK123', $mandate->debtorbankid);
    }

    /** @test */
    public function it_sets_debtorreference_property(): void
    {
        $mandate = new Mandate(['debtorreference' => 'DEBTOR-REF-001']);

        $this->assertSame('DEBTOR-REF-001', $mandate->debtorreference);
    }

    /** @test */
    public function it_sets_sequencetype_property(): void
    {
        $mandate = new Mandate(['sequencetype' => 1.0]);

        $this->assertSame(1.0, $mandate->sequencetype);
    }

    /** @test */
    public function it_sets_purchaseid_property(): void
    {
        $mandate = new Mandate(['purchaseid' => 'PURCHASE-123']);

        $this->assertSame('PURCHASE-123', $mandate->purchaseid);
    }

    /** @test */
    public function it_sets_mandateid_property(): void
    {
        $mandate = new Mandate(['mandateid' => 'MANDATE-456']);

        $this->assertSame('MANDATE-456', $mandate->mandateid);
    }

    /** @test */
    public function it_sets_language_property(): void
    {
        $mandate = new Mandate(['language' => 'nl']);

        $this->assertSame('nl', $mandate->language);
    }

    /** @test */
    public function it_sets_emandatereason_property(): void
    {
        $mandate = new Mandate(['emandatereason' => 'Subscription payment']);

        $this->assertSame('Subscription payment', $mandate->emandatereason);
    }

    /** @test */
    public function it_sets_maxamount_property(): void
    {
        $mandate = new Mandate(['maxamount' => 500.00]);

        $this->assertSame(500.00, $mandate->maxamount);
    }

    /** @test */
    public function it_sets_original_mandate_id_property(): void
    {
        $mandate = new Mandate(['originalMandateId' => 'ORIG-MANDATE-789']);

        $this->assertSame('ORIG-MANDATE-789', $mandate->originalMandateId);
    }

    /** @test */
    public function it_sets_multiple_properties_via_constructor(): void
    {
        $mandate = new Mandate([
            'debtorbankid' => 'BANK456',
            'debtorreference' => 'REF-002',
            'sequencetype' => 2.0,
            'purchaseid' => 'PUR-789',
            'mandateid' => 'MAN-012',
            'language' => 'en',
            'emandatereason' => 'Recurring payment',
            'maxamount' => 1000.00,
            'originalMandateId' => 'ORIG-999',
        ]);

        $this->assertSame('BANK456', $mandate->debtorbankid);
        $this->assertSame('REF-002', $mandate->debtorreference);
        $this->assertSame(2.0, $mandate->sequencetype);
        $this->assertSame('PUR-789', $mandate->purchaseid);
        $this->assertSame('MAN-012', $mandate->mandateid);
        $this->assertSame('en', $mandate->language);
        $this->assertSame('Recurring payment', $mandate->emandatereason);
        $this->assertSame(1000.00, $mandate->maxamount);
        $this->assertSame('ORIG-999', $mandate->originalMandateId);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $mandate = new Mandate([
            'debtorbankid' => 'BANK999',
            'debtorreference' => 'REF-999',
            'sequencetype' => 1.0,
            'purchaseid' => 'PUR-999',
            'mandateid' => 'MAN-999',
            'language' => 'fr',
            'emandatereason' => 'Subscription',
            'maxamount' => 750.00,
            'originalMandateId' => 'ORIG-888',
        ]);

        $array = $mandate->toArray();

        $this->assertIsArray($array);
        $this->assertSame('BANK999', $array['debtorbankid']);
        $this->assertSame('REF-999', $array['debtorreference']);
        $this->assertSame(1.0, $array['sequencetype']);
        $this->assertSame('PUR-999', $array['purchaseid']);
        $this->assertSame('MAN-999', $array['mandateid']);
        $this->assertSame('fr', $array['language']);
        $this->assertSame('Subscription', $array['emandatereason']);
        $this->assertSame(750.00, $array['maxamount']);
        $this->assertSame('ORIG-888', $array['originalMandateId']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $mandate = new Mandate([]);

        $array = $mandate->toArray();
        $this->assertIsArray($array);
    }
}
