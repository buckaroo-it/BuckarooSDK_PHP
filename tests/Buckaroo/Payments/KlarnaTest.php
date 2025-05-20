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

class KlarnaTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_klarna_payment()
    {
        $response = $this->buckaroo->method('klarna')->pay($this->getPaymentPayload());

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_klarna_payment_installment()
    {
        $response = $this->buckaroo->method('klarna')->payInInstallments($this->getPaymentPayload('GB', 'GBP'));

        $this->assertTrue($response->isPendingProcessing());
    }

    private function getPaymentPayload(string $country = 'NL', string $currency = 'EUR'): array
    {
        $payload = array_merge(
            $this->getBasePayPayload([],[
                'currency' => $currency,
            ]),
            [
                'billing' => $this->getBillingPayload(['careOf', 'title', 'initials']),
                'shipping' => $this->getShippingPayload(['careOf', 'title', 'initials']),
                'articles' => $this->getArticlesPayload(),
            ]
            );
        $payload['billing']['address']['country'] = $country;
        $payload['billing']['recipient']['gender'] = 'female';
        $payload['shipping']['address']['country'] = $country;
        $payload['shipping']['recipient']['gender'] = 'male';

        return $payload;
    }
}
