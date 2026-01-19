<?php

declare(strict_types=1);

namespace Tests\Feature\PaymentMethods;

use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class CreditManagementTest extends TestCase
{
    /** @test */
    public function it_creates_invoice(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $invoiceNumber = 'INV-' . TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'CM01', 'Description' => 'Invoice created'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'CreditManagement3',
                        'Action' => 'CreateInvoice',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => $invoiceNumber,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('credit_management')->createInvoice([
            'invoice' => $invoiceNumber,
            'invoiceDate' => '2026-01-14',
            'dueDate' => '2026-02-14',
            'debtor' => [
                'code' => 'DEBTOR-001',
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals($invoiceNumber, $response->getInvoice());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_combined_invoice(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $invoiceNumber = 'INV-CMB-' . TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'CM02', 'Description' => 'Combined invoice created'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'CreditManagement3',
                        'Action' => 'CreateCombinedInvoice',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => $invoiceNumber,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('credit_management')->createCombinedInvoice([
            'invoice' => $invoiceNumber,
            'invoiceDate' => '2026-01-14',
            'debtor' => [
                'code' => 'DEBTOR-COMBINED',
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals($invoiceNumber, $response->getInvoice());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_credit_note(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $creditNoteNumber = 'CN-' . TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'CM03', 'Description' => 'Credit note created'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'CreditManagement3',
                        'Action' => 'CreateCreditNote',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => $creditNoteNumber,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('credit_management')->createCreditNote([
            'invoice' => $creditNoteNumber,
            'originalInvoiceNumber' => 'INV-ORIGINAL-001',
            'debtor' => [
                'code' => 'DEBTOR-CN',
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals($creditNoteNumber, $response->getInvoice());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_adds_or_updates_debtor(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'CM04', 'Description' => 'Debtor updated'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'CreditManagement3',
                        'Action' => 'AddOrUpdateDebtor',
                        'Parameters' => [
                            ['Name' => 'Code', 'Value' => 'DEBTOR-UPDATE-001'],
                        ],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('credit_management')->addOrUpdateDebtor([
            'code' => 'DEBTOR-UPDATE-001',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());

        $params = $response->getServiceParameters();
        $this->assertEquals('DEBTOR-UPDATE-001', $params['code']);
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_payment_plan(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'CM05', 'Description' => 'Payment plan created'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'CreditManagement3',
                        'Action' => 'CreatePaymentPlan',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('credit_management')->createPaymentPlan([
            'paymentPlanGuid' => 'PLAN-GUID-' . TestHelpers::generateTransactionKey(),
            'description' => 'Monthly payment plan',
            'numberOfInstallments' => 12,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_terminates_payment_plan(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $planGuid = 'PLAN-GUID-TERMINATE-' . TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'CM06', 'Description' => 'Payment plan terminated'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'CreditManagement3',
                        'Action' => 'TerminatePaymentPlan',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('credit_management')->terminatePaymentPlan([
            'paymentPlanGuid' => $planGuid,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_pauses_invoice(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $invoiceNumber = 'INV-PAUSE-001';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'CM07', 'Description' => 'Invoice paused'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'CreditManagement3',
                        'Action' => 'PauseInvoice',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => $invoiceNumber,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('credit_management')->pauseInvoice([
            'invoice' => $invoiceNumber,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals($invoiceNumber, $response->getInvoice());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_unpauses_invoice(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $invoiceNumber = 'INV-UNPAUSE-001';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'CM08', 'Description' => 'Invoice unpaused'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'CreditManagement3',
                        'Action' => 'UnPauseInvoice',
                        'Parameters' => [],
                    ],
                ],
                'Invoice' => $invoiceNumber,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('credit_management')->unpauseInvoice([
            'invoice' => $invoiceNumber,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals($invoiceNumber, $response->getInvoice());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_retrieves_invoice_info(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();
        $invoiceNumber = 'INV-INFO-001';

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'CM09', 'Description' => 'Invoice info retrieved'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'CreditManagement3',
                        'Action' => 'InvoiceInfo',
                        'Parameters' => [
                            ['Name' => 'InvoiceNumber', 'Value' => $invoiceNumber],
                            ['Name' => 'Status', 'Value' => 'Open'],
                            ['Name' => 'Amount', 'Value' => '150.00'],
                        ],
                    ],
                ],
                'Invoice' => $invoiceNumber,
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('credit_management')->invoiceInfo([
            'invoice' => $invoiceNumber,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertEquals($invoiceNumber, $response->getInvoice());

        $params = $response->getServiceParameters();
        $this->assertEquals($invoiceNumber, $params['invoicenumber']);
        $this->assertEquals('Open', $params['status']);
        $this->assertEquals('150.00', $params['amount']);
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_retrieves_debtor_info(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'CM10', 'Description' => 'Debtor info retrieved'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'CreditManagement3',
                        'Action' => 'DebtorInfo',
                        'Parameters' => [
                            ['Name' => 'Code', 'Value' => 'DEBTOR-INFO-001'],
                            ['Name' => 'FirstName', 'Value' => 'Jane'],
                            ['Name' => 'LastName', 'Value' => 'Smith'],
                            ['Name' => 'Email', 'Value' => 'jane.smith@example.com'],
                        ],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('credit_management')->debtorInfo([
            'code' => 'DEBTOR-INFO-001',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());

        $params = $response->getServiceParameters();
        $this->assertEquals('DEBTOR-INFO-001', $params['code']);
        $this->assertEquals('Jane', $params['firstname']);
        $this->assertEquals('Smith', $params['lastname']);
        $this->assertEquals('jane.smith@example.com', $params['email']);
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_adds_or_updates_product_lines(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'CM11', 'Description' => 'Product lines updated'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'CreditManagement3',
                        'Action' => 'AddOrUpdateProductLines',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('credit_management')->addOrUpdateProductLines([
            'invoice' => 'INV-PRODUCTS-001',
            'articles' => [
                [
                    'identifier' => 'PRODUCT-001',
                    'description' => 'Test Product',
                    'quantity' => 2,
                    'price' => 50.00,
                ],
            ],
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_resumes_debtor_file(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'CM12', 'Description' => 'Debtor file resumed'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'CreditManagement3',
                        'Action' => 'ResumeDebtorFile',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('credit_management')->resumeDebtorFile([
            'debtorCode' => 'DEBTOR-RESUME-001',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_pauses_debtor_file(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'CM13', 'Description' => 'Debtor file paused'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'CreditManagement3',
                        'Action' => 'PauseDebtorFile',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('credit_management')->pauseDebtorFile([
            'debtorCode' => 'DEBTOR-PAUSE-001',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());
        $this->assertTrue($response->get('IsTest'));
    }

    /**
     * @test
     * @dataProvider statusCodeProvider
     */
    public function it_handles_various_status_codes(int $statusCode, string $assertMethod): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => $statusCode, 'Description' => 'Status'],
                    'SubCode' => ['Code' => 'CM01', 'Description' => 'Sub status'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'CreditManagement3',
                        'Action' => 'CreateInvoice',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('credit_management')->createInvoice([
            'invoice' => 'INV-STATUS-TEST',
            'invoiceDate' => '2026-01-14',
            'debtor' => [
                'code' => 'DEBTOR-STATUS',
            ],
        ]);

        if ($assertMethod === 'getStatusCode')
        {
            $this->assertEquals($statusCode, $response->getStatusCode());
        } else {
            $this->assertTrue($response->$assertMethod());
        }
    }

    public static function statusCodeProvider(): array
    {
        return [
            'success' => [190, 'isSuccess'],
            'failed' => [490, 'isFailed'],
            'validation_failure' => [491, 'isValidationFailure'],
            'rejected' => [690, 'isRejected'],
            'waiting_on_user_input' => [790, 'isWaitingOnUserInput'],
            'pending_processing' => [791, 'isPendingProcessing'],
            'cancelled' => [890, 'isCanceled'],
        ];
    }
}
