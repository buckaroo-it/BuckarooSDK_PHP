<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\In3\Models;

use Buckaroo\Models\Person as BasePerson;
use Buckaroo\PaymentMethods\In3\Models\Company;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    /** @test */
    public function it_extends_base_person_model(): void
    {
        $company = new Company([]);

        $this->assertInstanceOf(BasePerson::class, $company);
    }

    /** @test */
    public function it_sets_customer_number(): void
    {
        $company = new Company(['customerNumber' => 'COMP-12345']);

        $this->assertSame('COMP-12345', $company->customerNumber);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $company = new Company(['customerNumber' => 'COMP-TEST']);

        $array = $company->toArray();

        $this->assertIsArray($array);
        $this->assertSame('COMP-TEST', $array['customerNumber']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $company = new Company([]);

        $array = $company->toArray();
        $this->assertIsArray($array);
    }
}
