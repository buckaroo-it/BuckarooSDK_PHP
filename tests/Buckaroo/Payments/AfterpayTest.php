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
use Buckaroo\Resources\Constants\RecipientCategory;

class AfterpayTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpay_payment()
    {
        $response = $this->buckaroo->method('afterpay')->setServiceVersion(3)->pay($this->getPaymentPayload());

        self::$payTransactionKey = $response->getTransactionKey();

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpay_authorize()
    {
        $response = $this->buckaroo->method('afterpay')->authorize($this->getPaymentPayload());

        self::$authorizeTransactionKey = $response->getTransactionKey();

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     * @depends it_creates_a_afterpay_authorize
     */
    public function it_creates_a_afterpay_cancelAuthorize()
    {
        if (empty(self::$authorizeTransactionKey)) {
            $this->markTestSkipped('Skipping cancelAuthorize: No authorization transaction key is set.');
        }

        $response = $this->buckaroo->method('afterpay')->cancelAuthorize($this->getPaymentPayload([
            'originalTransactionKey' => self::$authorizeTransactionKey,
            'amountCredit' => 100.30,
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     * @depends it_creates_a_afterpay_authorize
     */
    public function it_creates_a_afterpay_capture()
    {
        $response = $this->buckaroo->method('afterpay')->setServiceVersion(3)->authorize($this->getPaymentPayload());

        $this->assertTrue($response->isSuccess());

        $response = $this->buckaroo->method('afterpay')->capture($this->getPaymentPayload([
            'originalTransactionKey' => $response->getTransactionKey(),
            'amountCredit' => 100.30,
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     * @depends it_creates_a_afterpay_payment
     */
    public function it_creates_a_afterpay_refund()
    {
        if (empty(self::$payTransactionKey)) {
            $this->markTestSkipped('Skipping refund: No original transaction key is set.');
        }

        $response = $this->buckaroo->method('afterpay')->refund(
            $this->getRefundPayload([
                'originalTransactionKey' => self::$payTransactionKey,
                'amountCredit' => 50.20,
                'articles' => [
                    [
                        'refundType' => 'Return',
                        'identifier' => 'Articlenumber1',
                        'description' => 'Blue Toy Car',
                        'vatPercentage' => '21',
                        'quantity' => '2',
                        'price' => '25.10',
                    ],
                ],
            ])
        );

        $this->assertTrue($response->isSuccess());
    }

    private function getPaymentPayload(?array $additional = null): array
    {
        $payload = array_merge($this->getBasePayPayload(), [
            'billing' => $this->getBillingPayload(['initials', 'title', 'companyName']),
            'shipping' => $this->getShippingPayload(['initials', 'title', 'companyName']),
            'articles' => $this->getArticlesPayload(),
        ]);

        $payload['billing']['recipient']['category'] = RecipientCategory::PERSON;
        $payload['shipping']['recipient']['category'] = RecipientCategory::PERSON;


        if ($additional) {
            $payload = array_merge($additional, $payload);
        }

        return $payload;
    }
}
