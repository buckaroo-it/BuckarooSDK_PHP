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
class EmandatesTest extends TestCase
{
    /** @test */
    public function it_retrieves_issuer_list(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Issuer list retrieved'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'emandate',
                        'Action' => 'GetIssuerList',
                        'Parameters' => [
                            ['Name' => 'Issuer', 'Value' => 'ABNANL2A'],
                            ['Name' => 'IssuerName', 'Value' => 'ABN AMRO'],
                        ],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('emandates')->issuerList();

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());

        $params = $response->getServiceParameters();
        $this->assertEquals('ABNANL2A', $params['issuer']);
        $this->assertEquals('ABN AMRO', $params['issuername']);
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_creates_mandate(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S002', 'Description' => 'Mandate created'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'emandate',
                        'Action' => 'CreateMandate',
                        'Parameters' => [
                            ['Name' => 'MandateId', 'Value' => 'MANDATE-123'],
                            ['Name' => 'DebtorReference', 'Value' => 'DEBTOR-REF-456'],
                        ],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('emandates')->createMandate([
            'currency' => 'EUR',
            'debtorbankid' => 'ABNANL2A',
            'debtorreference' => 'DEBTOR-REF-456',
            'sequencetype' => 1,
            'purchaseid' => 'PURCHASE-789',
            'language' => 'NL',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());

        $params = $response->getServiceParameters();
        $this->assertEquals('MANDATE-123', $params['mandateid']);
        $this->assertEquals('DEBTOR-REF-456', $params['debtorreference']);
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_retrieves_mandate_status(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S003', 'Description' => 'Status retrieved'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'emandate',
                        'Action' => 'GetStatus',
                        'Parameters' => [
                            ['Name' => 'MandateId', 'Value' => 'MANDATE-STATUS-123'],
                            ['Name' => 'Status', 'Value' => 'Active'],
                        ],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('emandates')->status([
            'currency' => 'EUR',
            'mandateid' => 'MANDATE-STATUS-123',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());

        $params = $response->getServiceParameters();
        $this->assertEquals('MANDATE-STATUS-123', $params['mandateid']);
        $this->assertEquals('Active', $params['status']);
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_modifies_mandate(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S004', 'Description' => 'Mandate modified'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'emandate',
                        'Action' => 'ModifyMandate',
                        'Parameters' => [
                            ['Name' => 'MandateId', 'Value' => 'MANDATE-MODIFY-789'],
                            ['Name' => 'MaxAmount', 'Value' => '500.00'],
                        ],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('emandates')->modifyMandate([
            'currency' => 'EUR',
            'mandateid' => 'MANDATE-MODIFY-789',
            'maxamount' => 500.00,
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());

        $params = $response->getServiceParameters();
        $this->assertEquals('MANDATE-MODIFY-789', $params['mandateid']);
        $this->assertEquals('500.00', $params['maxamount']);
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_cancels_mandate(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S005', 'Description' => 'Mandate cancelled'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'emandate',
                        'Action' => 'CancelMandate',
                        'Parameters' => [
                            ['Name' => 'MandateId', 'Value' => 'MANDATE-CANCEL-456'],
                        ],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('emandates')->cancelMandate([
            'currency' => 'EUR',
            'mandateid' => 'MANDATE-CANCEL-456',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());

        $params = $response->getServiceParameters();
        $this->assertEquals('MANDATE-CANCEL-456', $params['mandateid']);
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
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Sub status'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'emandate',
                        'Action' => 'CreateMandate',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('emandates')->createMandate([
            'currency' => 'EUR',
            'debtorbankid' => 'ABNANL2A',
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
