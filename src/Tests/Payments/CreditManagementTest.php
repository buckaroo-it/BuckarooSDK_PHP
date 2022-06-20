<?php

namespace Buckaroo\Tests\Payments;

use Buckaroo\Resources\Constants\CreditManagementInstallmentInterval;
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
        $response = $this->buckaroo->payment('credit_management')->createInvoice($this->invoice());

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_combined_invoice()
    {
        $response = $this->buckaroo->payment('sepadirectdebit')->attachCreditManagementInvoice($this->invoice())
                ->pay([
                    'amountDebit'       => 10.10,
                    'iban'              => 'NL13TEST0123456789',
                    'bic'               => 'TESTNL2A',
                    'collectdate'       => carbon()->addDays(60)->format('Y-m-d'),
                    'mandateReference'  => '1DCtestreference',
                    'mandateDate'       => '2022-07-03',
                    'customer'          => [
                        'name'          => 'John Smith'
                    ]
                ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_credit_note()
    {
        $response = $this->buckaroo->payment('credit_management')->createCreditNote([
                'originalInvoiceNumber' => 'testinvoice1337',
                'invoiceDate'           => carbon()->format('Y-m-d'),
                'invoiceAmount'         => 10.00,
                'invoiceAmountVAT'      => 1.00,
                'sendCreditNoteMessage' => 'info@buckaroo.nl'
            ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_add_or_update_debtor()
    {
        $response = $this->buckaroo->payment('credit_management')->addOrUpdateDebtor($this->invoice([
            'addressUnreachable'    => false,
            'emailUnreachable'      => false,
            'mobileUnreachable'     => false
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_payment_plan()
    {
        $response = $this->buckaroo->payment('credit_management')->createPaymentPlan([
            'description'               => 'Payment in two intstallments',
            'includedInvoiceKey'        => '20D09973FB5C4DBC9A33DB0F4F707xxx',
            'dossierNumber'             => 'PaymentplanJohnsmith123',
            'installmentCount'          => 2,
            'initialAmount'             => 100,
            'startDate'                 => carbon()->addDays(20)->format('Y-m-d'),
            'interval'                  => CreditManagementInstallmentInterval::MONTH,
            'paymentPlanCostAmount'     => 3.50,
            'paymentPlanCostAmountVat'  => 1.20,
            'recipientEmail'            => 'test@buckaroo.nl'
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_terminate_payment_plan()
    {
        $response = $this->buckaroo->payment('credit_management')->terminatePaymentPlan([
            'includedInvoiceKey'        => '20D09973FB5C4DBC9A33DB0F4F707xxx',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_pause_invoice()
    {
        $response = $this->buckaroo->payment('credit_management')->pauseInvoice([
            'invoice'               => 'Testinvoice184915'
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_unpause_invoice()
    {
        $response = $this->buckaroo->payment('credit_management')->unpauseInvoice([
            'invoice'               => 'Testinvoice184915'
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_invoice_info()
    {
        $response = $this->buckaroo->payment('credit_management')->invoiceInfo([
            'invoice'               => 'INV001',
            'invoices' => [ // If you want to check multiple invoices
                [
                    'invoiceNumber' => 'INV002'
                ],
                [
                    'invoiceNumber' => 'INV003'
                ],
            ]
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_debtor_info()
    {
        $response = $this->buckaroo->payment('credit_management')->debtorInfo([
            'debtor' => [
                'code'               => 'TestDebtor123123'
            ]
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_add_or_update_product_lines()
    {
        $response = $this->buckaroo->payment('credit_management')->addOrUpdateProductLines([
            'invoiceKey'      => 'xxxxxxxxxxxxxxxxxxxxxxxx',
            'articles'      => [
                [
                    'type'          => 'Regular',
                    'identifier' => 'Articlenumber1',
                    'description' => 'Blue Toy Car',
                    'vatPercentage' => '21',
                    'totalVat'       => 12,
                    'totalAmount'   => 123,
                    'quantity' => '2',
                    'price' => '20.10'
                ],
                [
                    'type'          => 'Regular',
                    'identifier' => 'Articlenumber2',
                    'description' => 'Red Toy Car',
                    'vatPercentage' => '21',
                    'totalVat'       => 12,
                    'totalAmount'   => 123,
                    'quantity' => '1',
                    'price' => '10.10'
                ],
            ]
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_resume_debtor_file()
    {
        $response = $this->buckaroo->payment('credit_management')->resumeDebtorFile([
            'debtorFileGuid'    => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_pause_debtor_file()
    {
        $response = $this->buckaroo->payment('credit_management')->pauseDebtorFile([
            'debtorFileGuid'    => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    private function invoice(array $append = []): array
    {
        return array_merge($append, [
            'invoice'               => str_random(),
            'applyStartRecurrent'   => 'False',
            'invoiceAmount'         => 10.00,
            'invoiceAmountVAT'      => 1.00,
            'invoiceDate'           => carbon()->format('Y-m-d'),
            'dueDate'           => carbon()->addDay(30)->format('Y-m-d'),
            'schemeKey'         => '2amq34',
            'maxStepIndex'      => 1,
            'allowedServices'   => 'ideal,mastercard',
            'debtor'        => [
                'code'  => 'johnsmith4'
            ],
            'email'     => 'youremail@example.nl',
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
    }

}