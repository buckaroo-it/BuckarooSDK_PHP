<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\CreditManagement\Models;

use Buckaroo\PaymentMethods\CreditManagement\Models\DebtorFile;
use Tests\TestCase;

class DebtorFileTest extends TestCase
{
    /** @test */
    public function it_sets_debtor_file_guid(): void
    {
        $debtorFile = new DebtorFile(['debtorFileGuid' => 'GUID-DEBTOR-123']);

        $this->assertSame('GUID-DEBTOR-123', $debtorFile->debtorFileGuid);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $debtorFile = new DebtorFile(['debtorFileGuid' => 'GUID-456']);

        $array = $debtorFile->toArray();

        $this->assertIsArray($array);
        $this->assertSame('GUID-456', $array['debtorFileGuid']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $debtorFile = new DebtorFile([]);

        $array = $debtorFile->toArray();
        $this->assertIsArray($array);
    }
}
