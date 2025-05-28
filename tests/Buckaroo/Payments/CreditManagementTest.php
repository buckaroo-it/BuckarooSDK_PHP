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
use Buckaroo\Resources\Constants\CreditManagementInstallmentInterval;
use Buckaroo\Resources\Constants\Gender;
use DateTime;

class CreditManagementTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_invoice()
    {
        $response = $this->buckaroo->method('credit_management')->createInvoice($this->getInvoicePayload(['invoice' => uniqid()]));

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_invoice_with_product_lines()
    {
        $response = $this->buckaroo->method('credit_management')->createInvoice([
            'invoice' => uniqid(),
            'description' => 'buckaroo_schema_test_PDF',
            'invoiceAmount' => 217.80,
            'invoiceDate' => '2022-01-01',
            'dueDate' => '2030-01-01',
            'schemeKey' => 's31w5d',
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

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_combined_invoice()
    {
        $invoice = $this->buckaroo->method('credit_management')->manually()->createCombinedInvoice($this->getInvoicePayload());

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

        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @return void
     * @test
     */
    //Get the invoice number from CreditManagements -> Invoices
    public function it_creates_a_credit_management_credit_note()
    {
        $response = $this->buckaroo->method('credit_management')->createCreditNote([
                'invoice' => uniqid(),
                'originalInvoiceNumber' => '682dc27aa66ef',
                'invoiceDate' => '2024-01-01',
                'invoiceAmount' => 10.00,
                'invoiceAmountVAT' => 1.00,
                'sendCreditNoteMessage' => 'Email',
            ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_add_or_update_debtor()
    {
        $response = $this->buckaroo->method('credit_management')->addOrUpdateDebtor($this->getInvoicePayload([
            'addressUnreachable' => false,
            'emailUnreachable' => false,
            'mobileUnreachable' => false
        ]));

        $this->assertTrue($response->isSuccess());
    }

    // /**
    //  * @return void
    //  * @test
    //  */
    //// Todo: Fix - No active payment plan could be found for the given invoice
    // public function it_creates_a_credit_management_payment_plan()
    // {
    //     $response = $this->buckaroo->method('credit_management')->createPaymentPlan([
    //         'description' => 'Payment in two intstallments',
    //         'includedInvoiceKey' => '7661B8F35D3B417EAF18439B5ED724E5',
    //         'dossierNumber' => 'PaymentplanJohnsmith123',
    //         'installmentCount' => 2,
    //         'initialAmount' => 2,
    //         'startDate' => (new DateTime('+90 days'))->format('Y-m-d'),
    //         'interval' => CreditManagementInstallmentInterval::MONTH,
    //         'paymentPlanCostAmount' => 1,
    //         // 'paymentPlanCostAmountVat' => 1.20,
    //         'recipientEmail' => 'test@buckaroo.nl',
    //     ]);

    //     $this->assertTrue($response->isSuccess());
    // }

    // /**
    //  * @return void
    //  * @test
    //  */
    // public function it_creates_a_credit_management_terminate_payment_plan()
    // {
    //     $response = $this->buckaroo->method('credit_management')->terminatePaymentPlan([
    //         'includedInvoiceKey' => '7661B8F35D3B417EAF18439B5ED724E5',
    //     ]);

    //     $this->assertTrue($response->isSuccess());
    // }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_pause_invoice()
    {
        $response = $this->buckaroo->method('credit_management')->pauseInvoice([
            'invoice' => '682dc27aa66ef',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_unpause_invoice()
    {
        $response = $this->buckaroo->method('credit_management')->unpauseInvoice([
            'invoice' => '682dc27aa66ef',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_invoice_info()
    {
        $response = $this->buckaroo->method('credit_management')->invoiceInfo([
            'invoice' => '682dc27aa66ef',
            'invoices' => [ // If you want to check multiple invoices
                [
                    'invoiceNumber' => 'INV001',
                ],
                [
                    'invoiceNumber' => 'INV002',
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_debtor_info()
    {
        $response = $this->buckaroo->method('credit_management')->debtorInfo([
            'debtor' => [
                'code' => 'johnsmith4',
            ],
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_credit_management_add_or_update_product_lines()
    {
        $response = $this->buckaroo->method('credit_management')->addOrUpdateProductLines([
            'invoiceKey' => '60F15FD00E164602A2080B09B9B26F4A',
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

        $this->assertTrue($response->isSuccess());
    }

    // /**
    //  * @return void
    //  * @test
    //  */
    //// No debtor files found
    // public function it_creates_a_credit_management_pause_debtor_file()
    // {
    //     $response = $this->buckaroo->method('credit_management')->pauseDebtorFile([
    //         'debtorFileGuid' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
    //     ]);

    //     $this->assertTrue($response->isValidationFailure());
    // }

    // /**
    //  * @return void
    //  * @test
    //  */
    // public function it_creates_a_credit_management_resume_debtor_file()
    // {
    //     $response = $this->buckaroo->method('credit_management')->resumeDebtorFile([
    //         'debtorFileGuid' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
    //     ]);

    //     $this->assertTrue($response->isValidationFailure());
    // }
}
