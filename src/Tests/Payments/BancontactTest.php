<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class BancontactTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_bancontact_payment()
    {
        $response = $this->buckaroo->payment('bancontactmrcash')->pay([
            'amountDebit' => 10.10,
            'saveToken' => true
        ]);

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @test
     */
    public function it_creates_a_bancontact_refund()
    {
        $response = $this->buckaroo->payment('bancontactmrcash')->refund([
            'amountCredit' => 10,
            'invoice' => '10000480',
            'originalTransactionKey' => '0EF39AA94BD64FF38F1540DEB6XXXXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_bancontact_encrypted_payment()
    {
        $response = $this->buckaroo->payment('bancontactmrcash')->payEncrypted([
            'amountDebit'       => 10.10,
            'description'       => 'Bancontact PayEncrypted Test 123',
            'encryptedCardData' => '001SlXfd8MbiTd/JFwCiGVs3f6o4x6xt0aN29NzOSNZHPKlVsz/EWeQmyhb1gGZ86VY88DP7gfDV+UyjcPfpVfHZd7u+WkO71hnV2QfYILCBNqE1aiPv2GQVGdaGbuoQloKu1o3o3I1UDmVxivXTMQX76ovot89geA6hqbtakmpmvxeiwwea3l4htNoX1IlD1hfYkDDl9rzSu5ypcjvVs6aRGXK5iMHnyrmEsEnfdj/Q5XWbsD5xAm4u3y6J8d4UP7LB31VLECzZUTiJOtKKcCQlT01YThIkQlj8PWBBMtt4H52VN3IH2+wPYtR8HiOZzcA2HA7UxozogIpS53tIURj/g=='
        ]);

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_bancontact_recurrent_payment()
    {
        $response = $this->buckaroo->payment('bancontactmrcash')->payRecurrent([
            'invoice'                   => 'testinvoice 123',
            'amountDebit'               => 10.50,
            'originalTransactionKey'    => '91D08EC01F414926A4CA29C059XXXXXX',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_bancontact_authenticate()
    {
        $response = $this->buckaroo->payment('bancontactmrcash')->authenticate([
            'invoice'                   => 'Bancontact Authenticate SaveToken',
            'description'               => 'Bancontact Authenticate SaveToken',
            'amountDebit'               => 0.02,
            'savetoken'                 => false
        ]);

        $this->assertTrue($response->isWaitingOnUserInput());
    }





}