<?php

declare(strict_types=1);

namespace Tests\Integration;

use Tests\TestCase;

/**
 * Integration tests for BuckarooClient.
 * These tests make real API calls and require valid credentials.
 */
class ClientTest extends TestCase
{
    /** @test */
    public function it_can_create_client_with_credentials(): void
    {
        $this->assertInstanceOf(\Buckaroo\BuckarooClient::class, $this->buckaroo);
    }

    /**
     * @test
     * @group slow
     */
    public function it_can_fetch_ideal_issuers(): void
    {
        $issuers = $this->buckaroo->method('ideal')->issuers();

        $this->assertIsArray($issuers);
    }
}
