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

class BillinkTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_billink_payment()
    {
        $response = $this->buckaroo->method('billink')->pay($this->getPayPayload([
            'trackAndTrace' => 'TR0F123456789',
            'vatNumber' => '2',
            'articles' => $this->getArticlesPayload(),
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     */
    public function it_creates_a_billink_authorize()
    {
        $response = $this->buckaroo->method('billink')->authorize($this->getPayPayload(
            [
                'trackAndTrace' => 'TR0F123456789',
                'vatNumber' => '2',
                'articles' => $this->getArticlesPayload(),
            ]
        ));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     */
    public function it_creates_a_billink_capture()
    {
        $authResponse = $this->buckaroo->method('billink')->authorize($this->getPayPayload(
            [
                'trackAndTrace' => 'TR0F123456789',
                'vatNumber' => '2',
                'articles' => $this->getArticlesPayload(),
            ]
        ));

        sleep(2);
        
        $response = $this->buckaroo->method('billink')->capture($this->getPayPayload(
                [
                    'originalTransactionKey' => $authResponse->getTransactionKey(),
                    'trackAndTrace' => 'TR0F123456789',
                    'vatNumber' => '2',
                    'articles' => $this->getArticlesPayload(),
                ]
            ));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     */
    public function it_creates_a_billink_refund()
    {
        $response = $this->buckaroo->method('billink')->refund($this->getRefundPayload([
            'originalTransactionKey' => 'A029CA3EB45642EEA1DC5F9CC3055711',
        ]));

        $this->assertTrue($response->isSuccess());
    }


    /**
     * @test
     */
    public function it_creates_a_billink_cancel_authorize()
    {
        $authResponse = $this->buckaroo->method('billink')->authorize($this->getPayPayload(
            [
                'trackAndTrace' => 'TR0F123456789',
                'vatNumber' => '2',
                'articles' => $this->getArticlesPayload(),
            ]
        ));

        sleep(2);
        $response = $this->buckaroo->method('billink')->cancelAuthorize($this->getRefundPayload([
            'originalTransactionKey' => $authResponse->getTransactionKey(),
            'amountCredit' => $authResponse->getAmount(),
        ]));

        $this->assertTrue($response->isSuccess());
    }
}
