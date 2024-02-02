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
        $response = $this->buckaroo->method('knaken')->pay([
            'invoice'               => uniqid(),
            'amountDebit'           => 10.99,
            'returnURL'             => 'https://buckaroo.dev./return',
            'returnURLCancel'       => 'https://buckaroo.dev/cancel',
            'returnURLError'        => 'https://buckaroo.dev/error',
            'returnURLReject'       => 'https://buckaroo.dev/reject',
            'pushURL'               => 'https://buckaroo.dev/push',
            'pushURLFailure'        => 'https://buckaroo.dev/push-failure',
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_knaken_refund()
    {
        $response = $this->buckaroo->method('knaken')->refund([
            'invoice' => '2024020209061234', //Set invoice number of the transaction to refund
            'originalTransactionKey' => '2FBB9F43A0AF4AC8B49F9073C0EC828B',
            //Set transaction key of the transaction to refund
            'amountCredit' => 0.01
        ]);

        $this->assertTrue($response->isFailed());
    }
}
