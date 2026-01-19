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
class SurepayTest extends TestCase
{
    /** @test */
    public function it_verifies_account_details(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/DataRequest*', [
                'Status' => [
                    'Code' => ['Code' => 190, 'Description' => 'Success'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Verification successful'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'surepay',
                        'Action' => 'verify',
                        'Parameters' => [
                            ['Name' => 'AccountName', 'Value' => 'John Doe'],
                            ['Name' => 'IBAN', 'Value' => 'NL91ABNA0417164300'],
                            ['Name' => 'Result', 'Value' => 'Match'],
                        ],
                    ]
                ],
            ]),
        ]);

        $response = $this->buckaroo->method('surepay')->verify([
            'accountName' => 'John Doe',
            'iban' => 'NL91ABNA0417164300',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @test
     * @dataProvider statusCodeProvider
     */
    public function it_handles_various_status_codes(int $statusCode, string $assertMethod): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('POST', '*/DataRequest*', [
                'Status' => [
                    'Code' => ['Code' => $statusCode, 'Description' => 'Status'],
                    'SubCode' => ['Code' => 'S001', 'Description' => 'Sub status'],
                    'DateTime' => date('Y-m-d\TH:i:s'),
                ],
                'Services' => [
                    [
                        'Name' => 'surepay',
                        'Action' => 'verify',
                        'Parameters' => [],
                    ]
                ],
            ]),
        ]);

        $response = $this->buckaroo->method('surepay')->verify([
            'accountName' => 'Test Account',
            'iban' => 'NL91ABNA0417164300',
        ]);

        if ($assertMethod === 'getStatusCode') {
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
            'cancelled' => [890, 'isCanceled'],
            'technical_error' => [492, 'getStatusCode'],
            'waiting_on_consumer' => [792, 'getStatusCode'],
        ];
    }
}
