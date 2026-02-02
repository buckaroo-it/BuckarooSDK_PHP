<?php

declare(strict_types=1);

namespace Tests\Unit\Handlers;

use Buckaroo\Handlers\Credentials;
use Tests\Support\BuckarooMockRequest;
use Tests\TestCase;

/**
 */
class CredentialsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    /** @test */
    public function it_confirms_valid_credentials(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                '*/Specification/ideal*',
                [
                    'Services' => [
                        ['Name' => 'ideal', 'Version' => 2],
                    ],
                ]
            ),
        ]);

        $credentials = new Credentials(
            $this->buckaroo->client(),
            $this->buckaroo->client()->config()
        );

        $result = $credentials->confirm();

        $this->assertTrue($result);
    }

    /** @test */
    public function it_returns_false_for_invalid_credentials(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                '*/Specification/ideal*',
                ['error' => 'Unauthorized'],
                401
            ),
        ]);

        $credentials = new Credentials(
            $this->buckaroo->client(),
            $this->buckaroo->client()->config()
        );

        $result = $credentials->confirm();

        $this->assertFalse($result);
    }

    /** @test */
    public function it_returns_false_when_exception_is_thrown(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                '*/Specification/ideal*',
                []
            )->withException(new \Buckaroo\Exceptions\BuckarooException(null, 'Connection error')),
        ]);

        $credentials = new Credentials(
            $this->buckaroo->client(),
            $this->buckaroo->client()->config()
        );

        $result = $credentials->confirm();

        $this->assertFalse($result);
    }
}
