<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\PayPerEmail\Models;

use Buckaroo\PaymentMethods\PayPerEmail\Models\Attachment;
use Tests\TestCase;

class AttachmentTest extends TestCase
{
    /** @test */
    public function it_sets_name(): void
    {
        $attachment = new Attachment(['name' => 'invoice.pdf']);

        $this->assertSame('invoice.pdf', $attachment->name);
    }

    /** @test */
    public function it_converts_to_array(): void
    {
        $attachment = new Attachment(['name' => 'receipt.pdf']);

        $array = $attachment->toArray();

        $this->assertIsArray($array);
        $this->assertSame('receipt.pdf', $array['name']);
    }

    /** @test */
    public function it_handles_empty_array_constructor(): void
    {
        $attachment = new Attachment([]);

        $array = $attachment->toArray();
        $this->assertIsArray($array);
    }

    /**
     * @test
     * @dataProvider attachmentNamesProvider
     */
    public function it_handles_various_attachment_names(string $name): void
    {
        $attachment = new Attachment(['name' => $name]);

        $this->assertSame($name, $attachment->name);
    }

    public static function attachmentNamesProvider(): array
    {
        return [
            ['invoice.pdf'],
            ['receipt.png'],
            ['document.docx'],
            ['terms-and-conditions.pdf'],
            ['order_confirmation_12345.pdf'],
        ];
    }
}
