<?php

namespace Tests\Buckaroo\Payments;

use Buckaroo\Resources\Constants\Gender;
use Tests\Buckaroo\BuckarooTestCase;

/**
 *
 */
class BatchTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_tests_batch_api()
    {
        $transactions = array();

        for($i=0;$i<3;$i++)
        {
            $invoice = $this->buckaroo->method('credit_management')->manually()->createCombinedInvoice([
                'invoice' => rand(1000, 9999),
                'applyStartRecurrent' => 'False',
                'invoiceAmount' => 10.00,
                'invoiceAmountVAT' => 1.00,
                'invoiceDate' => date('Y-m-d'),
                'dueDate' => date('Y-m-d'),
                'schemeKey' => '2amq34',
                'maxStepIndex' => 1,
                'allowedServices' => 'ideal,mastercard',
                'debtor' => [
                    'code' => 'johnsmith4',
                ],
                'email' => 'youremail@example.nl',
                'phone' => [
                    'mobile' => '06198765432',
                ],
                'person' => [
                    'culture' => 'nl-NL',
                    'title' => 'Msc',
                    'initials' => 'JS',
                    'firstName' => 'Test',
                    'lastNamePrefix' => 'Jones',
                    'lastName' => 'Aflever',
                    'gender' => Gender::MALE,
                ],
                'company' => [
                    'culture' => 'nl-NL',
                    'name' => 'My Company Corporation',
                    'vatApplicable' => true,
                    'vatNumber' => 'NL140619562B01',
                    'chamberOfCommerce' => '20091741',
                ],
                'address' => [
                    'street' => 'Hoofdtraat',
                    'houseNumber' => '90',
                    'houseNumberSuffix' => 'A',
                    'zipcode' => '8441ER',
                    'city' => 'Heerenveen',
                    'state' => 'Friesland',
                    'country' => 'NL',
                ],
            ]);

            $transactions[]  = $this->buckaroo->method('sepadirectdebit')->combine($invoice)->manually()->pay([
                'invoice' => uniqid(),
                'amountDebit' => 10.10,
                'iban' => 'NL13TEST0123456789',
                'bic' => 'TESTNL2A',
                'collectdate' => date('Y-m-d'),
                'mandateReference' => '1DCtestreference',
                'mandateDate' => '2022-07-03',
                'customer' => [
                    'name' => 'John Smith',
                ],
            ]);
        }

        $response = $this->buckaroo->batch($transactions)->execute();

        $this->assertTrue($response->data('Message') == '3 data requests were queued for processing.');
    }
}
