<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class EmandatesTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_get_emandates_issuer_list()
    {
        $response = $this->buckaroo->method('emandates')->issuerList();

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_mandante_on_emandates()
    {
        $response = $this->buckaroo->method('emandates')->createMandate([
            'emandatereason'    => 'testing',
            'sequencetype'      => '1',
            'purchaseid'        => 'purchaseid1234',
            'debtorbankid'      => 'INGBNL2A',
            'debtorreference'   => 'klant1234',
            'language'          => 'nl',
            'mandateid'         => '1DC1234567890'
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_get_status_from_emandates()
    {
        $response = $this->buckaroo->method('emandates')->status([
            'mandateid'         => '1DC1234567890'
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_modifies_mandante_on_emandates()
    {
        $response = $this->buckaroo->method('emandates')->modifyMandate([
            'originalMandateId'         => '1DC1234567890',
            'debtorbankid'      => 'ABNANL2A',
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_cancels_mandante_on_emandates()
    {
        $response = $this->buckaroo->method('emandates')->cancelMandate([
            'mandateid'         => '1DC1234567890',
            'emandatereason'    => 'testing cancel',
            'purchaseid'        => 'purchaseid1234',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }
}