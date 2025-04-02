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

class Przelewy24Test extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_przelewy24_payment()
    {
        $response = $this->buckaroo->method("przelewy24")->pay($this->getBasePayPayload([],[
            'email' => 'test@test.nl',
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Smith',
            ],
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_przelewy24_refund()
    {
        $response = $this->buckaroo->method('przelewy24')->refund($this->getRefundPayload([
            'originalTransactionKey' => '2C3A786E7F2A40B0A070483B19BEF84A',
        ]));

        $this->assertTrue($response->isSuccess());
    }
}
