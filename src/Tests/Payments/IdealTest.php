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

use Buckaroo\Config\Config;
use Buckaroo\Tests\BuckarooTestCase;

class CustomConfig extends Config
{
    public function __construct()
    {
        $websiteKey = 'Set Key';
        $secretKey = 'From other resources like DB/ENV/Platform Config';

        parent::__construct($websiteKey, $secretKey);
    }
}

class IdealTest extends BuckarooTestCase
{
    protected function setUp(): void
    {
        $this->paymentPayload = ([
            'invoice' => uniqid(),
            'amountDebit' => 10.10,
            'issuer' => 'ABNANL2A',
            'pushURL' => 'https://buckaroo.nextto.dev/push',
            'returnURL' => 'https://buckaroo.nextto.dev/return',
            'clientIP' => [
                'address' => '123.456.789.123',
                'type' => 0,
            ],
            'additionalParameters' => [
                'initiated_by_magento' => 1,
                'service_action' => 'something',
            ],
        ]);

        $this->refundPayload = [
            'invoice' => 'testinvoice 123', //Set invoice number of the transaction to refund
            'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX', //Set transaction key of the transaction to refund
            'amountCredit' => 1.23,
            'clientIP' => [
                'address' => '123.456.789.123',
                'type' => 0,
            ],
            'additionalParameters' => [
                'initiated_by_magento' => '1',
                'service_action' => 'something',
            ],
        ];
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_ideal_payment()
    {
        $response = $this->buckaroo->method('idealprocessing')->pay($this->paymentPayload);

        $this->assertTrue($response->isPendingProcessing());

//        $customConfig = new CustomConfig();
//        $customConfig->currency('AUD');
//
//        $response = $this->buckaroo->setConfig($customConfig)->payment('ideal')->pay(json_encode($this->paymentPayload));
//        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_ideal_refund()
    {
        $response = $this->buckaroo->method('ideal')->refund($this->refundPayload);

        $this->assertTrue($response->isFailed());
    }
}
