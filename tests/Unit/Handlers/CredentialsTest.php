<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers;

use Buckaroo\Handlers\Credentials;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class CredentialsTest extends TestCase
{
    public function test_confirms_valid_credentials_with_200_response(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                '*/Transaction/Specification/ideal*',
                TestHelpers::successResponse([
                    'Key' => 'SPEC-123',
                    'ServiceList' => [
                        [
                            'Name' => 'ideal',
                            'SupportedCurrencies' => 'EUR',
                        ],
                    ],
                ]),
                200
            )
        ]);

        $credentials = new Credentials($this->buckaroo->client(), $this->buckaroo->client()->config());
        $isValid = $credentials->confirm();

        $this->assertTrue($isValid);
    }

    public function test_rejects_credentials_for_non_200_status_codes(): void
    {
        $statusCodes = [400, 401, 403, 404, 500, 503];

        foreach ($statusCodes as $statusCode) {
            $this->mockBuckaroo->mockTransportRequests([
                BuckarooMockRequest::json(
                    'GET',
                    '*/Transaction/Specification/ideal*',
                    TestHelpers::failedResponse("HTTP {$statusCode}"),
                    $statusCode
                )
            ]);

            $credentials = new Credentials($this->buckaroo->client(), $this->buckaroo->client()->config());
            $isValid = $credentials->confirm();

            $this->assertFalse($isValid, "Expected false for HTTP {$statusCode}");
        }
    }
}
