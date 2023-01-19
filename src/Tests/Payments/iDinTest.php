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

class iDinTest extends BuckarooTestCase
{
    /**
     * @test
     */
    public function it_identify_with_idin()
    {
        $response = $this->buckaroo->method('idin')->identify([
            'issuer' => 'BANKNL2Y',
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_verify_with_idin()
    {
        $response = $this->buckaroo->method('idin')->verify([
            'issuer' => 'BANKNL2Y',
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_login_with_idin()
    {
        $response = $this->buckaroo->method('idin')->login([
            'issuer' => 'BANKNL2Y',
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }
}
