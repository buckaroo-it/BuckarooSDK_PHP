<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class iDinTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_identify_with_idin()
    {
        $response = $this->buckaroo->payment('idin')->identify([
            'issuer' => 'BANKNL2Y'
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_verify_with_idin()
    {
        $response = $this->buckaroo->payment('idin')->verify([
            'issuer' => 'BANKNL2Y'
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_login_with_idin()
    {
        $response = $this->buckaroo->payment('idin')->login([
            'issuer' => 'BANKNL2Y'
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }
}