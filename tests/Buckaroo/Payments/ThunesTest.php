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

class ThunesTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_thunes_paymentit_creates_a_thunes_payment()
    {
        $response = $this->buckaroo->method('thunes')->pay($this->getPaymentPayload());

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    private function getPaymentPayload(?array $additional = null): array
    {
        $payload = [
            'amountDebit'       => 3,
            'order'             => uniqid(),
            'invoice'           => uniqid(),
            'name' => 'monizzeecovoucher',
            'clientIP'      => '127.0.0.1',
            'articles' => [
                [
                    'identifier' => 'Articlenumber1',
                    'description' => 'Articledesciption1',
                    'price' => '1',
                ],
                [
                    'identifier' => 'Articlenumber2',
                    'description' => 'Articledesciption2',
                    'price' => '2',
                ],
            ]
        ];

        if ($additional)
        {
            return array_merge($additional, $payload);
        }

        return $payload;
    }
}
