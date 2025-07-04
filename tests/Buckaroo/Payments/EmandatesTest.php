<?php
/*
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * It is available through the world-wide-web at this URL:
 * https://tldrlegal.com/license/mit-license
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to support@buckaroo.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact support@buckaroo.nl for more information.
 *
 * @copyright Copyright (c) Buckaroo B.V.
 * @license   https://tldrlegal.com/license/mit-license
 */

namespace Tests\Buckaroo\Payments;

use Tests\Buckaroo\BuckarooTestCase;

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
            'emandatereason' => 'testing',
            'sequencetype' => '1',
            'purchaseid' => 'purchaseid1234',
            'debtorbankid' => 'INGBNL2A',
            'debtorreference' => 'klant1234',
            'language' => 'nl',
            'mandateid' => '1DC' . strtoupper(uniqid(mt_rand())),
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
            'mandateid' => '1DC1234567890',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_modifies_mandante_on_emandates()
    {
        $response = $this->buckaroo->method('emandates')->modifyMandate([
            'originalMandateId' => '1DC1234567890',
            'debtorbankid' => 'ABNANL2A',
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    //todo: "Unknown action 'CancelMandate' used on service 'emandate'."
    /**
     * @return void
     * @test
     */
    public function it_cancels_mandante_on_emandates()
    {
        $response = $this->buckaroo->method('emandates')->cancelMandate([
            'mandateid' => '1DC169627947667DC2DB3C7DEA',
            'emandatereason' => 'testing cancel',
            'purchaseid' => 'purchaseid 1234',
        ]);

        $this->assertTrue($response->isSuccess());
    }
}
