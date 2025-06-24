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
        $response = $this->buckaroo->method('bancontactmrcash')->pay($this->getBasePayPayload([],[
            'saveToken' => true,
        ]));

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @test
     */
    public function it_creates_a_bancontact_refund()
    {
        $response = $this->buckaroo->method('bancontactmrcash')->refund($this->getRefundPayload([
            'originalTransactionKey' => '77FDD0E0CF9C4AF1B85CEA2942DE27DC',
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_bancontact_encrypted_payment()
    {
        $response = $this->buckaroo->method('bancontactmrcash')->payEncrypted($this->getBasePayPayload([],[
            'encryptedCardData' => '001SlXfd8MbiTd/JFwCiGVs3f6o4x6xt0aN29NzOSNZHPKlVsz/EWeQmyhb1gGZ86VY88DP7gf
            DV+UyjcPfpVfHZd7u+WkO71hnV2QfYILCBNqE1aiPv2GQVGdaGbuoQloKu1o3o3I1UDmVxivXTMQX76ovot89geA6hqbtakmpm
            vxeiwwea3l4htNoX1IlD1hfYkDDl9rzSu5ypcjvVs6aRGXK5iMHnyrmEsEnfdj/Q5XWbsD5xAm4u3y6J8d4UP7LB31VLECzZUT
            iJOtKKcCQlT01YThIkQlj8PWBBMtt4H52VN3IH2+wPYtR8HiOZzcA2HA7UxozogIpS53tIURj/g==',
        ]));

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_bancontact_recurring_payment()
    {
        $response = $this->buckaroo->method('bancontactmrcash')->payRecurring($this->getBasePayPayload([], [
            'originalTransactionKey' => '77FDD0E0CF9C4AF1B85CEA2942DE27DC',
            'order' => '',
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_bancontact_pay_one_click_payment()
    {
        $response = $this->buckaroo->method('bancontactmrcash')->payOneClick($this->getBasePayPayload([],[
            'originalTransactionKey' => '77FDD0E0CF9C4AF1B85CEA2942DE27DC',
            'order' => '',
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_bancontact_authorize()
    {
        $response = $this->buckaroo->method('bancontactmrcash')->authorize($this->getBasePayPayload([], [
            'savetoken' => false,
        ]));

        self::$authorizeTransactionKey = $response->getTransactionKey();

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @return void
     * @test
     */
    // Replace the transaction key with the key from a successful authorization
    public function it_creates_a_bancontact_cancel_authorize()
    {
        $response = $this->buckaroo->method('bancontactmrcash')->cancelAuthorize($this->getRefundPayload([
            'originalTransactionKey' => self::$authorizeTransactionKey,
            'amountCredit' => 100.30,
            'amount' => 100.30,
            'creditAmount' => 100.30,

        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    // Replace the transaction key with the key from a successful authorization
    public function it_creates_a_bancontact_capture()
    {
        $response = $this->buckaroo->method('bancontactmrcash')->authorize($this->getBasePayPayload([], [
            'savetoken' => false,
        ]));

        self::$authorizeTransactionKey = $response->getTransactionKey();

        $response = $this->buckaroo->method('bancontactmrcash')->capture($this->getBasePayPayload([], [
            'originalTransactionKey' => self::$authorizeTransactionKey,
        ]));

        $this->assertTrue($response->isSuccess());
    }
}
