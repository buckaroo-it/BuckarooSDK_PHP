<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Resources\Constants\Gender;
use Buckaroo\Tests\BuckarooTestCase;

class CreditManagementTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_invoice()
    {
        $response = $this->buckaroo->payment('credit_management')->createInvoice([
            'invoice'               => str_random(),
            'applyStartRecurrent'   => 'False',
            'invoiceAmount'         => 10.00,
            'invoiceAmountVAT'      => 1.00,
            'invoiceDate'           => carbon()->format('Y-m-d'),
            'dueDate'           => carbon()->addDay(30)->format('Y-m-d'),
            'schemeKey'         => str_random(12),
            'maxStepIndex'      => 2,
            'allowedServices'   => 'ideal,mastercard',
            'debtor'        => [
                'code'  => 'johnsmith4'
            ],
            'email'     => [
                'email'     => 'youremail@example.nl'
            ],
            'phone'     => [
                'mobile'     => '06198765432'
            ],
            'person'      => [
                'culture'   => 'nl-NL',
                'title'     => 'Msc',
                'initials'  => 'JS',
                'firstName' => 'Test',
                'lastNamePrefix' => 'Jones',
                'lastName' => 'Aflever',
                'gender'   => Gender::MALE
            ],
            'company'       => [
                'culture'       => 'nl-NL',
                'name'          => 'My Company Corporation',
                'vatApplicable' => true,
                'vatNumber'     => 'NL140619562B01',
                'chamberOfCommerce' => '20091741'
            ],
            'address'   => [
                'street'            => 'Hoofdtraat',
                'houseNumber'       => '90',
                'houseNumberSuffix' => 'A',
                'zipcode'           => '8441ER',
                'city'              => 'Heerenveen',
                'state'             => 'Friesland',
                'country'           => 'NL'
            ]
        ]);

        dd($response);
        $this->assertTrue($response->isPendingProcessing());
    }
}