<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Buckaroo\Models\Article;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    public function test_initializes_all_properties_from_constructor(): void
    {
        $article = new Article([
            'identifier' => 'SKU-12345',
            'type' => 'physical',
            'brand' => 'Acme Corp',
            'manufacturer' => 'Acme Manufacturing',
            'unitCode' => 'PCS',
            'price' => 99.99,
            'quantity' => 5,
            'vatPercentage' => 21.0,
            'vatCategory' => 'high',
            'description' => 'Premium product with warranty',
        ]);

        $this->assertSame('SKU-12345', $article->identifier);
        $this->assertSame('physical', $article->type);
        $this->assertSame('Acme Corp', $article->brand);
        $this->assertSame('Acme Manufacturing', $article->manufacturer);
        $this->assertSame('PCS', $article->unitCode);
        $this->assertSame(99.99, $article->price);
        $this->assertSame(5, $article->quantity);
        $this->assertSame(21.0, $article->vatPercentage);
        $this->assertSame('high', $article->vatCategory);
        $this->assertSame('Premium product with warranty', $article->description);
    }

    public function test_handles_partial_initialization(): void
    {
        $article = new Article([
            'identifier' => 'SKU-001',
            'price' => 49.50,
            'quantity' => 1,
        ]);

        $this->assertSame('SKU-001', $article->identifier);
        $this->assertSame(49.50, $article->price);
        $this->assertSame(1, $article->quantity);
        $this->assertNull($article->type);
        $this->assertNull($article->brand);
        $this->assertNull($article->vatCategory);
    }

    public function test_to_array_preserves_type_integrity(): void
    {
        $article = new Article([
            'identifier' => 'TEST-123',
            'type' => 'digital',
            'price' => 19.99,
            'quantity' => 3,
            'vatPercentage' => 9.0,
        ]);

        $array = $article->toArray();

        $this->assertIsString($array['identifier']);
        $this->assertIsString($array['type']);
        $this->assertIsFloat($array['price']);
        $this->assertIsInt($array['quantity']);
        $this->assertIsFloat($array['vatPercentage']);

        $this->assertSame('TEST-123', $array['identifier']);
        $this->assertSame('digital', $array['type']);
        $this->assertSame(19.99, $array['price']);
        $this->assertSame(3, $array['quantity']);
        $this->assertSame(9.0, $array['vatPercentage']);
    }

    public function test_float_properties_preserve_precision(): void
    {
        $article = new Article([
            'identifier' => 'FLOAT-TEST',
            'price' => 99.99,
            'vatPercentage' => 21.5,
        ]);

        $this->assertSame(99.99, $article->price);
        $this->assertSame(21.5, $article->vatPercentage);

        $array = $article->toArray();
        $this->assertSame(99.99, $array['price']);
        $this->assertSame(21.5, $array['vatPercentage']);
    }

    public function test_float_properties_handle_zero_and_edge_values(): void
    {
        $article = new Article([
            'identifier' => 'EDGE-001',
            'price' => 0.0,
            'quantity' => 0,
            'vatPercentage' => 0.0,
        ]);

        $this->assertSame(0.0, $article->price);
        $this->assertSame(0, $article->quantity);
        $this->assertSame(0.0, $article->vatPercentage);

        $array = $article->toArray();
        $this->assertIsFloat($array['price']);
        $this->assertIsInt($array['quantity']);
        $this->assertIsFloat($array['vatPercentage']);
        $this->assertSame(0.0, $array['price']);
        $this->assertSame(0, $array['quantity']);
    }

    public function test_integer_quantity_handles_edge_cases(): void
    {
        $negativeQuantity = new Article([
            'identifier' => 'NEG-001',
            'quantity' => -5,
        ]);
        $this->assertSame(-5, $negativeQuantity->quantity);
        $this->assertIsInt($negativeQuantity->toArray()['quantity']);

        $largeQuantity = new Article([
            'identifier' => 'LARGE-001',
            'quantity' => 999999,
        ]);
        $this->assertSame(999999, $largeQuantity->quantity);
        $this->assertIsInt($largeQuantity->toArray()['quantity']);

        $zeroQuantity = new Article([
            'identifier' => 'ZERO-001',
            'quantity' => 0,
        ]);
        $this->assertSame(0, $zeroQuantity->quantity);
    }

    public function test_string_properties_handle_empty_and_special_characters(): void
    {
        $article = new Article([
            'identifier' => '',
            'type' => 'digital',
            'brand' => '',
            'description' => 'Special chars: €100, 50% off, <tag>, "quoted", \'single\'',
        ]);

        $this->assertSame('', $article->identifier);
        $this->assertSame('', $article->brand);
        $this->assertSame('Special chars: €100, 50% off, <tag>, "quoted", \'single\'', $article->description);

        $array = $article->toArray();
        $this->assertSame('', $array['identifier']);
        $this->assertSame('', $array['brand']);
        $this->assertSame('Special chars: €100, 50% off, <tag>, "quoted", \'single\'', $array['description']);
    }
}
