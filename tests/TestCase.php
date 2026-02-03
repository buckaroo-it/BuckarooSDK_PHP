<?php

declare(strict_types=1);

namespace Tests;

use Buckaroo\BuckarooClient;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Tests\Support\MockBuckaroo;

abstract class TestCase extends BaseTestCase
{
    protected BuckarooClient $buckaroo;
    protected MockBuckaroo $mockBuckaroo;
    private bool $mockEnabled = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockBuckaroo = new MockBuckaroo();
    }

    /**
     * Enable HTTP mocking for this test.
     * Call this method before using mockTransportRequests().
     * IMPORTANT: Must be called before createBuckarooClient().
     */
    protected function useMock(): void
    {
        $this->mockEnabled = true;
        $this->mockBuckaroo->install();
        $this->buckaroo = $this->createBuckarooClient();
    }

    protected function tearDown(): void
    {
        if ($this->mockEnabled) {
            $this->mockBuckaroo->assertAllConsumed();
        }
        MockBuckaroo::clearInstance();

        parent::tearDown();
    }

    protected function createBuckarooClient(array $overrides = []): BuckarooClient
    {
        return new BuckarooClient(
            $overrides['websiteKey'] ?? $_ENV['BPE_WEBSITE_KEY'],
            $overrides['secretKey'] ?? $_ENV['BPE_SECRET_KEY'],
            $overrides['mode'] ?? 'test'
        );
    }
}
