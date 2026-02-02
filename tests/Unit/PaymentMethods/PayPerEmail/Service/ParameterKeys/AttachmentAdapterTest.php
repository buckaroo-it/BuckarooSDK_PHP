<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\PayPerEmail\Service\ParameterKeys;

use Buckaroo\Models\Model;
use Buckaroo\PaymentMethods\PayPerEmail\Service\ParameterKeys\AttachmentAdapter;
use Tests\TestCase;

class AttachmentAdapterTest extends TestCase
{
    private function createAttachment(array $data): Model
    {
        return new class($data) extends Model {
            protected string $name;
            protected string $type;
            protected string $content;
        };
    }

    public function test_transforms_name_to_attachment(): void
    {
        $attachment = $this->createAttachment(['name' => 'document.pdf']);
        $adapter = new AttachmentAdapter($attachment);

        $this->assertSame('attachment', $adapter->serviceParameterKeyOf('name'));
    }

    public function test_uses_ucfirst_for_unmapped_properties(): void
    {
        $attachment = $this->createAttachment(['type' => 'application/pdf']);
        $adapter = new AttachmentAdapter($attachment);

        $this->assertSame('Type', $adapter->serviceParameterKeyOf('type'));
        $this->assertSame('Content', $adapter->serviceParameterKeyOf('content'));
    }

    public function test_proxies_property_access_to_model(): void
    {
        $attachment = $this->createAttachment([
            'name' => 'invoice.pdf',
            'type' => 'application/pdf',
            'content' => 'base64encodedcontent',
        ]);

        $adapter = new AttachmentAdapter($attachment);

        $this->assertSame('invoice.pdf', $adapter->name);
        $this->assertSame('application/pdf', $adapter->type);
        $this->assertSame('base64encodedcontent', $adapter->content);
    }

    public function test_delegates_to_array_to_model(): void
    {
        $attachment = $this->createAttachment([
            'name' => 'receipt.pdf',
            'type' => 'application/pdf',
        ]);

        $adapter = new AttachmentAdapter($attachment);
        $array = $adapter->toArray();

        $this->assertIsArray($array);
        $this->assertSame('receipt.pdf', $array['name']);
        $this->assertSame('application/pdf', $array['type']);
    }

    public function test_handles_various_file_names(): void
    {
        $fileNames = [
            'document.pdf',
            'invoice-2024.pdf',
            'receipt_123.jpg',
            'contract v2.docx',
            'attachment.txt',
        ];

        foreach ($fileNames as $fileName) {
            $attachment = $this->createAttachment(['name' => $fileName]);
            $adapter = new AttachmentAdapter($attachment);

            $this->assertSame($fileName, $adapter->name);
            $this->assertSame('attachment', $adapter->serviceParameterKeyOf('name'));
        }
    }
}
