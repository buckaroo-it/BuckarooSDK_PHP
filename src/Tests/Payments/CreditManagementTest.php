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
        $response = $this->buckaroo->method('credit_management')->createInvoice($this->invoice());

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_invoice_with_product_lines()
    {
        $response = $this->buckaroo->method('credit_management')->createInvoice([
            'invoice' => 'Billingtest101',
            'description' => 'buckaroo_schema_test_PDF',
            'invoiceAmount' => 217.80,
            'invoiceDate' => '2022-01-01',
            'dueDate' => '1990-01-01',
            'schemeKey' => '2amq34',
            'poNumber' => 'PO-12345',
            'debtor' => [
                'code' => 'johnsmith4',
            ],
            'articles' => [
                [
                    'productGroupName' => 'Toys',
                    'productGroupOrderIndex' => 1,
                    'productOrderIndex' => 1,
                    'type' => 'Regular',
                    'identifier' => 'ART12',
                    'description' => 'Blue Toy Car',
                    'quantity' => 3,
                    'unitOfMeasurement' => 'piece(s)',
                    'price' => 10,
                    'discountPercentage' => 20,
                    'totalDiscount' => 6,
                    'vatPercentage' => 21,
                    'totalVat' => 0.6,
                    'totalAmountExVat' => 8.40,
                    'totalAmount' => 123,
                ],
                [
                    'productGroupName' => 'Toys',
                    'productGroupOrderIndex' => 1,
                    'productOrderIndex' => 2,
                    'type' => 'Regular',
                    'identifier' => 'ART12',
                    'description' => 'Blue Toy Car',
                    'quantity' => 3,
                    'unitOfMeasurement' => 'piece(s)',
                    'price' => 10,
                    'discountPercentage' => 20,
                    'totalDiscount' => 6,
                    'vatPercentage' => 21,
                    'totalVat' => 0.6,
                    'totalAmountExVat' => 8.40,
                    'totalAmount' => 123,
                ],
            ],
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_combined_invoice()
    {
        $invoice = $this->buckaroo->method('credit_management')->manually()->createCombinedInvoice($this->invoice());

        $response = $this->buckaroo->method('sepadirectdebit')->combine($invoice)
                ->pay([
                    'invoice' => uniqid(),
                    'amountDebit' => 10.10,
                    'iban' => 'NL13TEST0123456789',
                    'bic' => 'TESTNL2A',
                    'collectdate' => '2030-01-01',
                    'mandateReference' => '1DCtestreference',
                    'mandateDate' => '2022-07-03',
                    'customer' => [
                        'name' => 'John Smith',
                    ],
                ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_credit_note()
    {
        $response = $this->buckaroo->method('credit_management')->createCreditNote([
                'originalInvoiceNumber' => 'testinvoice1337',
                'invoiceDate' => '2022-01-01',
                'invoiceAmount' => 10.00,
                'invoiceAmountVAT' => 1.00,
                'sendCreditNoteMessage' => 'info@buckaroo.nl',
            ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_add_or_update_debtor()
    {
        $response = $this->buckaroo->method('credit_management')->addOrUpdateDebtor($this->invoice([
            'addressUnreachable' => false,
            'emailUnreachable' => false,
            'mobileUnreachable' => false,
        ]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_payment_plan()
    {
        $response = $this->buckaroo->method('credit_management')->createPaymentPlan([
            'description' => 'Payment in two intstallments',
            'includedInvoiceKey' => '20D09973FB5C4DBC9A33DB0F4F707xxx',
            'dossierNumber' => 'PaymentplanJohnsmith123',
            'installmentCount' => 2,
            'initialAmount' => 100,
            'startDate' => '2030-01-01',
            'interval' => CreditManagementInstallmentInterval::MONTH,
            'paymentPlanCostAmount' => 3.50,
            'paymentPlanCostAmountVat' => 1.20,
            'recipientEmail' => 'test@buckaroo.nl',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_terminate_payment_plan()
    {
        $response = $this->buckaroo->method('credit_management')->terminatePaymentPlan([
            'includedInvoiceKey' => '20D09973FB5C4DBC9A33DB0F4F707xxx',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_pause_invoice()
    {
        $response = $this->buckaroo->method('credit_management')->pauseInvoice([
            'invoice' => 'Testinvoice184915',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_unpause_invoice()
    {
        $response = $this->buckaroo->method('credit_management')->unpauseInvoice([
            'invoice' => 'Testinvoice184915',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_invoice_info()
    {
        $response = $this->buckaroo->method('credit_management')->invoiceInfo([
            'invoice' => 'INV001',
            'invoices' => [ // If you want to check multiple invoices
                [
                    'invoiceNumber' => 'INV002',
                ],
                [
                    'invoiceNumber' => 'INV003',
                ],
            ],
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_debtor_info()
    {
        $response = $this->buckaroo->method('credit_management')->debtorInfo([
            'debtor' => [
                'code' => 'TestDebtor123123',
            ],
        ]);

        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_add_or_update_product_lines()
    {
        $response = $this->buckaroo->method('credit_management')->addOrUpdateProductLines([
            'invoiceKey' => 'xxxxxxxxxxxxxxxxxxxxxxxx',
            'articles' => [
                [
                    'type' => 'Regular',
                    'identifier' => 'Articlenumber1',
                    'description' => 'Blue Toy Car',
                    'vatPercentage' => '21',
                    'totalVat' => 12,
                    'totalAmount' => 123,
                    'quantity' => '2',
                    'price' => '20.10',
                ],
                [
                    'type' => 'Regular',
                    'identifier' => 'Articlenumber2',
                    'description' => 'Red Toy Car',
                    'vatPercentage' => '21',
                    'totalVat' => 12,
                    'totalAmount' => 123,
                    'quantity' => '1',
                    'price' => '10.10',
                ],
            ],
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_resume_debtor_file()
    {
        $response = $this->buckaroo->method('credit_management')->resumeDebtorFile([
            'debtorFileGuid' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_pause_debtor_file()
    {
        $response = $this->buckaroo->method('credit_management')->pauseDebtorFile([
            'debtorFileGuid' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    private function invoice(array $append = []): array
    {
        return array_merge($append, [
            'invoice' => rand(1000, 99999999),
            'applyStartRecurrent' => 'False',
            'invoiceAmount' => 10.00,
            'invoiceAmountVAT' => 1.00,
        'invoiceDate' => '2022-01-01',
            'dueDate' => '2030-01-01',
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
    }
}
