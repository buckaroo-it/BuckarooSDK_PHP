<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class MarketplacesTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_marketplaces_split()
    {
        $marketplace = $this->buckaroo->payment('marketplaces')->manually()->split([
            'daysUntilTransfer' => 2,
            'marketplace'       => [
                'amount'        => 10,
                'description'   => 'INV0001 Commission Marketplace'
            ],
            'sellers'            => [
                [
                    'accountId'     => '789C60F316D24B088ACD471',
                    'amount'        => 50,
                    'description'   => 'INV001 Payout Make-Up Products BV'
                ],
                [
                    'accountId'     => '369C60F316D24B088ACD238',
                    'amount'        => 35,
                    'description'   => 'INV0001 Payout Beauty Products BV'
                ]
            ]
        ]);

        $response = $this->buckaroo->payment('ideal')->combine($marketplace)->pay([
            'invoice'       => uniqid(),
            'amountDebit' => 95.00,
            'issuer' => 'ABNANL2A'
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @test
     */
    public function it_creates_marketplaces_transfer()
    {
        $response = $this->buckaroo->payment('marketplaces')->transfer([
            'originalTransactionKey'   => 'D3732474ED0',
            'marketplace'       => [
                'amount'        => 10,
                'description'   => 'INV0001 Commission Marketplace'
            ],
            'sellers'            => [
                [
                    'accountId'     => '789C60F316D24B088ACD471',
                    'amount'        => 50,
                    'description'   => 'INV001 Payout Make-Up Products BV'
                ]
            ]
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @test
     */
    public function it_creates_marketplaces_refund()
    {
        $marketplace = $this->buckaroo->payment('marketplaces')->manually()->refundSupplementary([
            'sellers'            => [
                [
                    'accountId'     => '789C60F316D24B088ACD471',
//                    'amount'        => 30,
                    'description'   => 'INV001 Payout Make-Up Products BV'
                ]
            ]
        ]);

        $response = $this->buckaroo->payment('ideal')->combine($marketplace)->refund([
            'invoice'   => 'testinvoice 123', //Set invoice number of the transaction to refund
            'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX', //Set transaction key of the transaction to refund
            'amountCredit' => 30
        ]);

        $this->assertTrue($response->isValidationFailure());
    }
}