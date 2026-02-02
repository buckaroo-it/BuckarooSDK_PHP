<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\iDin\Service\ParameterKeys;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\iDin\Service\ParameterKeys\IssuerAdapter;
use Tests\TestCase;

class IssuerAdapterTest extends TestCase
{
    private function createIssuer(array $data): Model
    {
        return new class($data) extends Model {
            protected string $issuer;
            protected string $name;
        };
    }

    public function test_transforms_issuer_to_issuer_id(): void
    {
        $issuer = $this->createIssuer(['issuer' => 'ABNANL2A']);
        $adapter = new IssuerAdapter($issuer);

        $this->assertSame('issuerId', $adapter->serviceParameterKeyOf('issuer'));
    }

    public function test_uses_ucfirst_for_unmapped_properties(): void
    {
        $issuer = $this->createIssuer(['name' => 'ABN AMRO']);
        $adapter = new IssuerAdapter($issuer);

        $this->assertSame('Name', $adapter->serviceParameterKeyOf('name'));
    }

    public function test_proxies_property_access_to_model(): void
    {
        $issuer = $this->createIssuer([
            'issuer' => 'INGBNL2A',
            'name' => 'ING Bank',
        ]);

        $adapter = new IssuerAdapter($issuer);

        $this->assertSame('INGBNL2A', $adapter->issuer);
        $this->assertSame('ING Bank', $adapter->name);
    }

    public function test_delegates_to_array_to_model(): void
    {
        $issuer = $this->createIssuer([
            'issuer' => 'RABONL2U',
            'name' => 'Rabobank',
        ]);

        $adapter = new IssuerAdapter($issuer);
        $array = $adapter->toArray();

        $this->assertIsArray($array);
        $this->assertSame('RABONL2U', $array['issuer']);
        $this->assertSame('Rabobank', $array['name']);
    }

    public function test_handles_various_issuer_codes(): void
    {
        $issuers = ['ABNANL2A', 'INGBNL2A', 'RABONL2U', 'SNSBNL2A', 'RBRBNL21'];

        foreach ($issuers as $issuerCode) {
            $issuer = $this->createIssuer(['issuer' => $issuerCode]);
            $adapter = new IssuerAdapter($issuer);

            $this->assertSame($issuerCode, $adapter->issuer);
            $this->assertSame('issuerId', $adapter->serviceParameterKeyOf('issuer'));
        }
    }
}
