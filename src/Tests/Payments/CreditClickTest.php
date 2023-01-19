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

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class CreditClickTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_creditclick_payment()
    {
        $response = $this->buckaroo->method('creditclick')->pay([
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'customer' => [
                'firstName' => 'Test',
                'lastName' => 'Aflever',
            ],
            'email' => 't.tester@test.nl',
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_creditclick_refund()
    {
        $response = $this->buckaroo->method('creditclick')->refund([
            'amountCredit' => 10,
            'invoice' => 'testinvoice 123',
            'description' => 'refund',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
            'refundreason' => 'RequestedByCustomer',
        ]);

        $this->assertTrue($response->isFailed());
    }
}
