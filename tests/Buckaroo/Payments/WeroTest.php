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

class WeroTest extends BuckarooTestCase
{

    /**
     * @return void
     * @test
     */
    public function it_creates_a_wero_payment()
    {
        $response = $this->buckaroo->method('Wero')->pay($this->getBasePayPayload());

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_wero_authorize()
    {
        $response = $this->buckaroo->method('Wero')->authorize($this->getBasePayPayload());

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_wero_cancel_authorize()
    {
        $response = $this->buckaroo->method('Wero')->cancelAuthorize($this->getRefundPayload([
            'originalTransactionKey' => 'B591116039094602B6D899A1XXXXXXXX',
            'amountCredit' => 100.30,

        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_wero_capture()
    {
        $response = $this->buckaroo->method('Wero')->capture($this->getBasePayPayload([], [
            'originalTransactionKey' => '981A2018935A4EADB374E479XXXXXXXX',
            'amountDebit' => 100.30,
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     */
    public function it_creates_a_wero_refund()
    {
        $response = $this->buckaroo->method('Wero')->refund(
            $this->getRefundPayload([
                'originalTransactionKey' => '13B9F93D925E4694AB5FB005XXXXXXXX',
            ])
        );

        $this->assertTrue($response->isSuccess());
    }
}
