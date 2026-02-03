<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\WeChatPay\Models;

use Buckaroo\PaymentMethods\WeChatPay\Models\Pay;
use Tests\TestCase;

class PayTest extends TestCase
{
    /** @test */
    public function it_sets_locale(): void
    {
        $pay = new Pay(['locale' => 'zh_CN']);

        $this->assertSame('zh_CN', $pay->locale);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $pay = new Pay(['locale' => 'en_US']);

        $array = $pay->toArray();

        $this->assertIsArray($array);
        $this->assertSame('en_US', $array['locale']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $pay = new Pay([]);

        $array = $pay->toArray();
        $this->assertIsArray($array);
    }

    /**
     * @test
     * @dataProvider localeProvider
     */
    public function it_handles_various_locales(string $locale): void
    {
        $pay = new Pay(['locale' => $locale]);

        $this->assertSame($locale, $pay->locale);
    }

    public static function localeProvider(): array
    {
        return [
            ['zh_CN'],
            ['en_US'],
            ['nl_NL'],
            ['de_DE'],
        ];
    }
}
