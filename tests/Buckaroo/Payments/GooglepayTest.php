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

class GooglepayTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_googlepay_payment()
    {
        $response = $this->buckaroo->payment('googlepay')->pay($this->getBasePayPayload([], [
            'paymentData' => 'XXXXXXXXXXXXX',
            'customerCardName' => 'XXXXXXXXXXXXX'
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_googlepay_refund()
    {
        $response = $this->buckaroo->method('googlepay')->refund($this->getRefundPayload([
            'originalTransactionKey' => '77FDD0E0CF9C4AF1B85CEA2942DE27DC',
        ]));

        $this->assertTrue($response->isFailed());
    }
}
