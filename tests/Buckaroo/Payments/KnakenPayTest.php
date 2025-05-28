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

class KnakenPayTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_knaken_payment()
    {
        $response = $this->buckaroo->method('knaken')->pay($this->getBasePayPayload([],[
            'returnURL'=> 'https://example.com/buckaroo/return',
            'returnURLCancel' => 'https://example.com/buckaroo/cancel',
            'returnURLError' => 'https://example.com/buckaroo/error',
            'returnURLReject' => 'https://example.com/buckaroo/reject',
            'pushURL' => 'https://example.com/buckaroo/push',
            'returnURLCancel' => 'https://example.com/buckaroo/cancel',
            'pushURLFailure' => 'https://example.com/buckaroo/push-failure',
            'invoice'               => uniqid(),
            'amountDebit'           => 0.1,
            "CustomerName"=> "Rico",
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    // Only one refund allowed per transaction
    /**
     * @test
     */
    public function it_creates_a_knaken_refund()
    {
        $response = $this->buckaroo->method('knaken')->refund($this->getRefundPayload([
            'originalTransactionKey' => 'E29EB7DF6EA8448A87FC6A03E6EFA0A3',
        ]));

        $this->assertTrue($response->isSuccess());
    }
}
