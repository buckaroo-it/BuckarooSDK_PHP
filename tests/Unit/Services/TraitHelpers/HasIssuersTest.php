<?php

declare(strict_types=1);

namespace Tests\Unit\Services\TraitHelpers;

use Tests\Support\BuckarooMockRequest;
use Tests\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class HasIssuersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }

    /** @test */
    public function it_fetches_issuers_list_via_ideal(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                '*/Specification/ideal*',
                [
                    'Actions' => [
                        [
                            'Name' => 'Pay',
                            'RequestParameters' => [
                                [
                                    'Name' => 'issuer',
                                    'ListItemDescriptions' => [
                                        ['Value' => 'ABNANL2A', 'Description' => 'ABN AMRO'],
                                        ['Value' => 'INGBNL2A', 'Description' => 'ING'],
                                        ['Value' => 'RABONL2U', 'Description' => 'Rabobank'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            ),
        ]);

        $issuers = $this->buckaroo->method('ideal')->issuers();

        $this->assertIsArray($issuers);
        $this->assertCount(3, $issuers);
        $this->assertSame(['id' => 'ABNANL2A', 'name' => 'ABN AMRO'], $issuers[0]);
        $this->assertSame(['id' => 'INGBNL2A', 'name' => 'ING'], $issuers[1]);
        $this->assertSame(['id' => 'RABONL2U', 'name' => 'Rabobank'], $issuers[2]);
    }

    /** @test */
    public function it_returns_empty_array_when_no_issuers_found(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                '*/Specification/ideal*',
                [
                    'Actions' => [
                        [
                            'Name' => 'Pay',
                            'RequestParameters' => [],
                        ],
                    ],
                ]
            ),
        ]);

        $issuers = $this->buckaroo->method('ideal')->issuers();

        $this->assertIsArray($issuers);
        $this->assertEmpty($issuers);
    }

    /** @test */
    public function it_returns_empty_array_on_api_error(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                '*/Specification/ideal*',
                []
            ),
        ]);

        $issuers = $this->buckaroo->method('ideal')->issuers();

        $this->assertIsArray($issuers);
        $this->assertEmpty($issuers);
    }

    /** @test */
    public function it_handles_empty_list_item_descriptions(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                '*/Specification/ideal*',
                [
                    'Actions' => [
                        [
                            'Name' => 'Pay',
                            'RequestParameters' => [
                                [
                                    'Name' => 'issuer',
                                    'ListItemDescriptions' => [],
                                ],
                            ],
                        ],
                    ],
                ]
            ),
        ]);

        $issuers = $this->buckaroo->method('ideal')->issuers();

        $this->assertIsArray($issuers);
        $this->assertEmpty($issuers);
    }
}
