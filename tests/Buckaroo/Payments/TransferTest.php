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
use Buckaroo\Resources\Constants\Gender;

class TransferTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_transfer_payment()
    {
        $response = $this->buckaroo->method('transfer')->pay($this->getBasePayPayload([],[
            'email' => 'your@email.com',
            'country' => 'NL',
            'dateDue' => date("Y-m-d"),
            'sendMail' => false,
            'customer' => [
                'gender' => Gender::MALE,
                'firstName' => 'John',
                'lastName' => 'Smith',
            ],
        ]));

        $this->assertTrue($response->isAwaitingConsumer());
    }

    /**
     * @test
     */
    public function it_creates_a_transfer_refund()
    {
        $response = $this->buckaroo->method('transfer')->refund($this->getRefundPayload([
            'originalTransactionKey' => 'CA18006C913A47E58D830C7D7CC42A6E',
        ]));

        $this->assertTrue($response->isSuccess());
    }
}
