<?php

declare(strict_types=1);

namespace Tests\Feature\PaymentMethods;

use Tests\FeatureTestCase;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class iDinTest extends FeatureTestCase
{
    /** @test */
    public function it_performs_identify_verification(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Identity verified'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'idin',
                        'Action' => 'Identify',
                        'Parameters' => [
                            ['Name' => 'Issuer', 'Value' => 'ABNANL2A'],
                            ['Name' => 'ConsumerBin', 'Value' => 'ABC123XYZ'],
                            ['Name' => 'ConsumerName', 'Value' => 'J. Doe'],
                        ],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('idin')->identify([
            'issuer' => 'ABNANL2A',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());

        $params = $response->getServiceParameters();
        $this->assertEquals('ABNANL2A', $params['issuer']);
        $this->assertEquals('ABC123XYZ', $params['consumerbin']);
        $this->assertEquals('J. Doe', $params['consumername']);
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_performs_verify_verification(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S002', 'Description' => 'Verification completed'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'idin',
                        'Action' => 'Verify',
                        'Parameters' => [
                            ['Name' => 'Issuer', 'Value' => 'INGBNL2A'],
                            ['Name' => 'ConsumerBin', 'Value' => 'XYZ789ABC'],
                            ['Name' => 'IsEighteenOrOlder', 'Value' => 'true'],
                        ],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('idin')->verify([
            'issuer' => 'INGBNL2A',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());

        $params = $response->getServiceParameters();
        $this->assertEquals('INGBNL2A', $params['issuer']);
        $this->assertEquals('XYZ789ABC', $params['consumerbin']);
        $this->assertEquals('true', $params['iseighteenorolder']);
        $this->assertTrue($response->get('IsTest'));
    }

    /** @test */
    public function it_performs_login_verification(): void
    {
        $transactionKey = TestHelpers::generateTransactionKey();

        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/json/DataRequest*', [
                'Key' => $transactionKey,
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S003', 'Description' => 'Login completed'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'idin',
                        'Action' => 'Login',
                        'Parameters' => [
                            ['Name' => 'Issuer', 'Value' => 'RABONL2U'],
                            ['Name' => 'ConsumerBin', 'Value' => 'LOGIN123BIN'],
                            ['Name' => 'SessionId', 'Value' => 'SESSION-456-XYZ'],
                        ],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('idin')->login([
            'issuer' => 'RABONL2U',
        ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals($transactionKey, $response->getTransactionKey());

        $params = $response->getServiceParameters();
        $this->assertEquals('RABONL2U', $params['issuer']);
        $this->assertEquals('LOGIN123BIN', $params['consumerbin']);
        $this->assertEquals('SESSION-456-XYZ', $params['sessionid']);
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
                        'Name' => 'idin',
                        'Action' => 'Identify',
                        'Parameters' => [],
                    ],
                ],
                'IsTest' => true,
            ]),
        ]);

        $response = $this->buckaroo->method('idin')->identify([
            'issuer' => 'ABNANL2A',
        ]);

        $this->assertTrue($response->$assertMethod());
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
