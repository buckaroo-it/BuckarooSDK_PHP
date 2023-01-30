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

class IdealQRTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_creates_a_ideal_qr()
    {
        $response = $this->buckaroo->method('ideal_qr')->generate([
            'description' => 'Test purchase',
            'minAmount' => '0.10',
            'maxAmount' => '10.0',
            'imageSize' => '2000',
            'purchaseId' => 'Testpurchase123',
            'isOneOff' => false,
            'amount' => '1.00',
            'amountIsChangeable' => true,
            'expiration' => '2030-09-30',
            'isProcessing' => false,
        ]);

        $this->assertTrue($response->isSuccess());
    }
}
