<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\CreditManagement\Models;

use Buckaroo\PaymentMethods\CreditManagement\Models\DebtorInfo;
use Buckaroo\PaymentMethods\CreditManagement\Service\ParameterKeys\DebtorInfoAdapter;
use Tests\TestCase;

class DebtorInfoTest extends TestCase
{
    /** @test */
    public function it_sets_debtor_from_array(): void
    {
        $debtorInfo = new DebtorInfo([
            'debtor' => [
                'code' => 'DEBTOR001',
            ],
        ]);

        $debtor = $debtorInfo->debtor();

        $this->assertInstanceOf(DebtorInfoAdapter::class, $debtor);
    }

    /** @test */
    public function it_returns_debtor_without_parameter(): void
    {
        $debtorInfo = new DebtorInfo([
            'debtor' => [
                'code' => 'DEBTOR002',
            ],
        ]);

        $debtor = $debtorInfo->debtor();
        $this->assertInstanceOf(DebtorInfoAdapter::class, $debtor);

        $sameDebtor = $debtorInfo->debtor(null);
        $this->assertSame($debtor, $sameDebtor);
    }
}
