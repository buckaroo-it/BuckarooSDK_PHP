<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\FeatureTestCase;
use Tests\Support\BuckarooMockRequest;

/**
 * Feature tests for BuckarooClient.
 * These tests use MockBuckaroo to simulate API responses.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ClientTest extends FeatureTestCase
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
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json('GET', '*/json/Transaction/Specification/ideal*', [
                'Actions' => [
                    [
                        'RequestParameters' => [
                            [
                                'ListItemDescriptions' => [
                                    [
                                        'Value' => 'ABNANL2A',
                                        'Description' => 'ABN AMRO Bank',
                                    ],
                                    [
                                        'Value' => 'INGBNL2A',
                                        'Description' => 'ING Bank',
                                    ],
                                    [
                                        'Value' => 'RABONL2U',
                                        'Description' => 'Rabobank',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $issuers = $this->buckaroo->method('ideal')->issuers();

        $this->assertIsArray($issuers);
        $this->assertNotEmpty($issuers);
        $this->assertCount(3, $issuers);
        $this->assertSame('ABNANL2A', $issuers[0]['id']);
        $this->assertSame('ABN AMRO Bank', $issuers[0]['name']);
    }
}
