<?php

declare(strict_types=1);

namespace Tests\Unit\Resources\Constants;

use Buckaroo\Resources\Constants\Endpoints;
use PHPUnit\Framework\TestCase;

class EndpointsTest extends TestCase
{
    public function test_live_endpoint(): void
    {
        $this->assertSame('https://checkout.buckaroo.nl/', Endpoints::LIVE);
    }

    public function test_test_endpoint(): void
    {
        $this->assertSame('https://testcheckout.buckaroo.nl/', Endpoints::TEST);
    }

    public function test_endpoints_are_https(): void
    {
        $this->assertStringStartsWith('https://', Endpoints::LIVE);
        $this->assertStringStartsWith('https://', Endpoints::TEST);
    }

    public function test_endpoints_end_with_slash(): void
    {
        $this->assertStringEndsWith('/', Endpoints::LIVE);
        $this->assertStringEndsWith('/', Endpoints::TEST);
    }

    public function test_endpoints_contain_buckaroo_domain(): void
    {
        $this->assertStringContainsString('buckaroo.nl', Endpoints::LIVE);
        $this->assertStringContainsString('buckaroo.nl', Endpoints::TEST);
    }

    public function test_test_endpoint_contains_test_prefix(): void
    {
        $this->assertStringContainsString('test', Endpoints::TEST);
    }

    public function test_endpoints_are_valid_urls(): void
    {
        $this->assertNotFalse(filter_var(Endpoints::LIVE, FILTER_VALIDATE_URL));
        $this->assertNotFalse(filter_var(Endpoints::TEST, FILTER_VALIDATE_URL));
    }
}
