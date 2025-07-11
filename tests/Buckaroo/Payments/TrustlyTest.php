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

class TrustlyTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_trustly_payment()
    {
        $response = $this->buckaroo->method('trustly')->pay($this->getBasePayPayload([], [
            'country' => 'NL',
            'continueOnIncomplete'=> true,
            'customer' => [
                'firstName' => 'Test',
                'lastName' => 'Aflever',
            ],

        ]));

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @test
     */
    public function it_creates_a_trustly_refund()
    {
        $response = $this->buckaroo->method('trustly')->refund($this->getRefundPayload([
            'originalTransactionKey' => '7F796BBC52664FCA936C4C3A1DD18996',
        ]));

        $this->assertTrue($response->isSuccess());
    }
}

