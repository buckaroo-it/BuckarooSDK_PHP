<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\CreditManagement\Service\ParameterKeys;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\CreditManagement\Service\ParameterKeys\DebtorInfoAdapter;
use Tests\TestCase;

class DebtorInfoAdapterTest extends TestCase
{
    private function createDebtorInfo(array $data): Model
    {
        return new class($data) extends Model {
            protected string $code;
            protected string $name;
            protected string $reference;
        };
    }

    public function test_transforms_code_to_debtor_code(): void
    {
        $debtorInfo = $this->createDebtorInfo(['code' => 'DEBTOR-123']);
        $adapter = new DebtorInfoAdapter($debtorInfo);

        $this->assertSame('DebtorCode', $adapter->serviceParameterKeyOf('code'));
    }

    public function test_uses_ucfirst_for_unmapped_properties(): void
    {
        $debtorInfo = $this->createDebtorInfo(['name' => 'Test Debtor']);
        $adapter = new DebtorInfoAdapter($debtorInfo);

        $this->assertSame('Name', $adapter->serviceParameterKeyOf('name'));
        $this->assertSame('Reference', $adapter->serviceParameterKeyOf('reference'));
    }

    public function test_proxies_property_access_to_model(): void
    {
        $debtorInfo = $this->createDebtorInfo([
            'code' => 'DEBTOR-456',
            'name' => 'Debtor Name',
            'reference' => 'REF-789',
        ]);

        $adapter = new DebtorInfoAdapter($debtorInfo);

        $this->assertSame('DEBTOR-456', $adapter->code);
        $this->assertSame('Debtor Name', $adapter->name);
        $this->assertSame('REF-789', $adapter->reference);
    }

    public function test_delegates_to_array_to_model(): void
    {
        $debtorInfo = $this->createDebtorInfo([
            'code' => 'CODE-123',
            'name' => 'Test Name',
        ]);

        $adapter = new DebtorInfoAdapter($debtorInfo);
        $array = $adapter->toArray();

        $this->assertIsArray($array);
        $this->assertSame('CODE-123', $array['code']);
        $this->assertSame('Test Name', $array['name']);
    }

    public function test_handles_various_code_formats(): void
    {
        $codes = ['DEBT-001', 'CUSTOMER-12345', 'DBT123', 'REF-ABC-123'];

        foreach ($codes as $code) {
            $debtorInfo = $this->createDebtorInfo(['code' => $code]);
            $adapter = new DebtorInfoAdapter($debtorInfo);

            $this->assertSame($code, $adapter->code);
            $this->assertSame('DebtorCode', $adapter->serviceParameterKeyOf('code'));
        }
    }
}
