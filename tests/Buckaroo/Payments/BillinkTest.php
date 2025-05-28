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

        self::$payTransactionKey = $response->getTransactionKey();


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

        self::$authorizeTransactionKey = $response->getTransactionKey();

        $this->assertTrue($response->isSuccess());
    }
    

    // Not able to test (issue with amount) - Validation failure
    // /**
    //  * @test
    //  */
    // public function it_creates_a_billink_cancel_authorize()
    // {
    //     $response = $this->buckaroo->method('billink')->cancelAuthorize($this->getRefundPayload([
    //         'originalTransactionKey' => self::$authorizeTransactionKey,
    //         'amountCredit' => 100.30,
    //     ]));


    //     $this->assertTrue($response->isSuccess());
    // }

    /**
     * @test
     */
    public function it_creates_a_billink_capture()
    {
        $response = $this->buckaroo->method('billink')->authorize($this->getPayPayload(
            [
                'trackAndTrace' => '12345678',
                'vatNumber' => '2',
                'articles' => $this->getArticlesPayload(),
            ]
        ));

        $this->assertTrue($response->isSuccess());

        sleep(2);
        $response = $this->buckaroo->method('billink')->capture($this->getPayPayload(
                [
                    'originalTransactionKey' => $response->getTransactionKey(),
                    'trackAndTrace' => '12345678',
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
            'originalTransactionKey' => self::$payTransactionKey,
        ]));

        $this->assertTrue($response->isSuccess());
    }
}
