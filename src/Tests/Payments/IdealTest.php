<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Buckaroo;
use PHPUnit\Framework\TestCase;

class IdealTest extends TestCase
{
    protected function setUp(): void
    {
        $this->buckaroo = new Buckaroo($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);
    }

    public function test_it_creates_a_ideal_payment()
    {
        $response = $this->buckaroo->payment('idealprocessing')->pay([
            'issuer' => 'ABNANL2A',
            'amountDebit' => 10.10
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }
}