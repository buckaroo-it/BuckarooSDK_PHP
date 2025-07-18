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

use Buckaroo\Resources\Constants\RecipientCategory;
use Tests\Buckaroo\BuckarooTestCase;
use Buckaroo\Resources\Constants\Gender;

class In3Test extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_in3_payment()
    {
        $response = $this->buckaroo->method('in3')->pay($this->getPayPayload([
            'billing' => $this->getBillingPayload(['title', 'conversationLanguage', 'identificationNumber']),
            'shipping' => $this->getShippingPayload(['title', 'conversationLanguage', 'identificationNumber']),
            'articles' => $this->getArticlesPayload(),
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_in3_refund()
    {
        $response = $this->buckaroo->method('in3')->refund($this->getRefundPayload([
            'originalTransactionKey' => 'B535EC6AC624431ABA27D849F44700BA',
        ]));

        $this->assertTrue($response->isSuccess());
    }
}
