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

class BancontactTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_bancontact_payment()
    {
        $response = $this->buckaroo->method('bancontactmrcash')->pay([
            'invoice' => uniqid(),
            'amountDebit' => 10.10,
            'saveToken' => true,
        ]);

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @test
     */
    public function it_creates_a_bancontact_refund()
    {
        $response = $this->buckaroo->method('bancontactmrcash')->refund([
            'amountCredit' => 10,
            'invoice' => '10000480',
            'originalTransactionKey' => '0EF39AA94BD64FF38F1540DEB6XXXXXX',
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_bancontact_encrypted_payment()
    {
        $response = $this->buckaroo->method('bancontactmrcash')->payEncrypted([
            'invoice' => uniqid(),
            'amountDebit' => 10.10,
            'description' => 'Bancontact PayEncrypted Test 123',
            'encryptedCardData' => '001SlXfd8MbiTd/JFwCiGVs3f6o4x6xt0aN29NzOSNZHPKlVsz/EWeQmyhb1gGZ86VY88DP7gf
            DV+UyjcPfpVfHZd7u+WkO71hnV2QfYILCBNqE1aiPv2GQVGdaGbuoQloKu1o3o3I1UDmVxivXTMQX76ovot89geA6hqbtakmpm
            vxeiwwea3l4htNoX1IlD1hfYkDDl9rzSu5ypcjvVs6aRGXK5iMHnyrmEsEnfdj/Q5XWbsD5xAm4u3y6J8d4UP7LB31VLECzZUT
            iJOtKKcCQlT01YThIkQlj8PWBBMtt4H52VN3IH2+wPYtR8HiOZzcA2HA7UxozogIpS53tIURj/g==',
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_bancontact_recurring_payment()
    {
        $response = $this->buckaroo->method('bancontactmrcash')->payRecurring([
            'invoice' => 'testinvoice 123',
            'amountDebit' => 10.50,
            'originalTransactionKey' => '91D08EC01F414926A4CA29C059XXXXXX',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_bancontact_pay_one_click_payment()
    {
        $response = $this->buckaroo->method('bancontactmrcash')->payOneClick([
            'invoice' => 'testinvoice 123',
            'amountDebit' => 10.50,
            'originalTransactionKey' => '91D08EC01F414926A4CA29C059XXXXXX',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_bancontact_authorize()
    {
        $response = $this->buckaroo->method('bancontactmrcash')->authorize([
            'invoice' => 'Bancontact Authenticate SaveToken',
            'description' => 'Bancontact Authenticate SaveToken',
            'amountDebit' => 0.02,
            'savetoken' => false,
        ]);

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_bancontact_capture()
    {
        $response = $this->buckaroo->method('bancontactmrcash')->capture([
            'invoice' => 'Bancontact Authenticate SaveToken',
            'description' => 'Bancontact Authenticate SaveToken',
            'originalTransactionKey'    => 'D3EEF5279D9047A0B202334D8050B6CF',
            'amountDebit' => 0.02,
        ]);

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_bancontact_cancel_authorize()
    {
        $response = $this->buckaroo->method('bancontactmrcash')->cancelAuthorize([
            'invoice'                   => 'Bancontact Authenticate SaveToken',
            'description'               => 'Bancontact Authenticate SaveToken',
            'originalTransactionKey'    => '122862F217D44C4DAF4012D93301E168',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }
}
