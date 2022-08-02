<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;

class SurepayTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_verify_with_surepay()
    {
        $response = $this->buckaroo->method('surepay')->verify([
            'bankAccount'   => [
                'iban'          => 'NL13TEST0123456789',
                'accountName'   => 'John Doe'
            ]
        ]);

        $this->assertTrue($response->isSuccess());
    }
}