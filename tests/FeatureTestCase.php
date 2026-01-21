<?php

declare(strict_types=1);

namespace Tests;

/**
 * Base class for Feature tests.
 *
 * Automatically enables HTTP mocking, so you don't need to call useMock() manually.
 * Use this for all Feature tests that need to mock HTTP requests.
 */
abstract class FeatureTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();
    }
}
