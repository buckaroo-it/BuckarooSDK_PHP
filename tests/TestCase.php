<?php

declare(strict_types=1);

namespace Tests;

use Buckaroo\BuckarooClient;
use Mockery;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Tests\Support\MockBuckaroo;

abstract class TestCase extends BaseTestCase
{
    protected BuckarooClient $buckaroo;
    protected MockBuckaroo $mockBuckaroo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockBuckaroo = new MockBuckaroo();
        $this->mockBuckaroo->install();

        $this->buckaroo = $this->createBuckarooClient();
    }

    protected function tearDown(): void
    {
        $this->mockBuckaroo->assertAllConsumed();
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
