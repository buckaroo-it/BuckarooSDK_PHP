<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class IdealQRTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_ideal_qr()
    {
        $response = $this->buckaroo->payment('ideal_qr')->generate([
            'serviceParameters' => [
                'ideal_qr'      => [
                    'description'           => 'Test purchase',
                    'minAmount'             => '0.10',
                    'maxAmount'             => '10.0',
                    'imageSize'             => '2000',
                    'purchaseId'            => 'Testpurchase123',
                    'isOneOff'              => false,
                    'amount'                => '1.00',
                    'amountIsChangeable'    => true,
                    'expiration'            => '2018-09-30',
                    'isProcessing'          => false
                ]
            ]
        ]);
        dd($response);
        $this->assertTrue($response->isSuccess());
    }
}