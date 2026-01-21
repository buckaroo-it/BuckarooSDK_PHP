<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Buckaroo\Models\CustomParameters;
use Tests\TestCase;

class CustomParametersTest extends TestCase
{
    public function test_transforms_to_list_format(): void
    {
        $params = new CustomParameters([
            'key1' => 'value1',
            'key2' => 'value2',
        ]);

        $array = $params->toArray();

        $this->assertArrayHasKey('List', $array);
        $this->assertCount(2, $array['List']);
        $this->assertSame('key1', $array['List'][0]['Name']);
        $this->assertSame('value1', $array['List'][0]['Value']);
        $this->assertSame('key2', $array['List'][1]['Name']);
        $this->assertSame('value2', $array['List'][1]['Value']);
    }

    public function test_handles_empty_parameters(): void
    {
        $params = new CustomParameters([]);

        $array = $params->toArray();

        $this->assertTrue(
            !isset($array['List']) || empty($array['List']),
            'Empty parameters should result in empty or missing List'
        );
    }

    public function test_handles_null_parameters(): void
    {
        $params = new CustomParameters(null);

        $array = $params->toArray();

        $this->assertTrue(
            !isset($array['List']) || empty($array['List']),
            'Null parameters should result in empty or missing List'
        );
    }

    public function test_handles_single_parameter(): void
    {
        $params = new CustomParameters(['singleKey' => 'singleValue']);

        $array = $params->toArray();

        $this->assertArrayHasKey('List', $array);
        $this->assertCount(1, $array['List']);
        $this->assertSame('singleKey', $array['List'][0]['Name']);
        $this->assertSame('singleValue', $array['List'][0]['Value']);
    }

    public function test_preserves_numeric_string_values(): void
    {
        $params = new CustomParameters([
            'amount' => '100.50',
            'quantity' => '5',
        ]);

        $array = $params->toArray();

        $this->assertCount(2, $array['List']);
        $this->assertSame('100.50', $array['List'][0]['Value']);
        $this->assertSame('5', $array['List'][1]['Value']);
    }

    public function test_handles_special_characters_in_values(): void
    {
        $params = new CustomParameters([
            'description' => 'Test & Payment <special>',
            'reference' => 'REF/123/456',
        ]);

        $array = $params->toArray();

        $this->assertSame('Test & Payment <special>', $array['List'][0]['Value']);
        $this->assertSame('REF/123/456', $array['List'][1]['Value']);
    }

    public function test_preserves_original_key_names(): void
    {
        $params = new CustomParameters([
            'CamelCaseKey' => 'value1',
            'snake_case_key' => 'value2',
            'mixedCase_Key' => 'value3',
        ]);

        $array = $params->toArray();

        $keys = array_column($array['List'], 'Name');

        $this->assertContains('CamelCaseKey', $keys);
        $this->assertContains('snake_case_key', $keys);
        $this->assertContains('mixedCase_Key', $keys);
    }

    public function test_multiple_set_properties_calls_accumulate_entries(): void
    {
        $params = new CustomParameters(['key1' => 'value1']);
        $params->setProperties(['key2' => 'value2']);
        $params->setProperties(['key3' => 'value3']);

        $array = $params->toArray();

        $this->assertCount(3, $array['List']);
        $this->assertSame('key1', $array['List'][0]['Name']);
        $this->assertSame('value1', $array['List'][0]['Value']);
        $this->assertSame('key2', $array['List'][1]['Name']);
        $this->assertSame('value2', $array['List'][1]['Value']);
        $this->assertSame('key3', $array['List'][2]['Name']);
        $this->assertSame('value3', $array['List'][2]['Value']);
    }

    public function test_preserves_boolean_types(): void
    {
        $params = new CustomParameters([
            'isActive' => true,
            'isDeleted' => false,
        ]);

        $array = $params->toArray();

        $this->assertSame(true, $array['List'][0]['Value']);
        $this->assertSame(false, $array['List'][1]['Value']);
    }

    public function test_preserves_integer_types(): void
    {
        $params = new CustomParameters([
            'count' => 42,
            'negative' => -100,
            'zero' => 0,
        ]);

        $array = $params->toArray();

        $this->assertSame(42, $array['List'][0]['Value']);
        $this->assertSame(-100, $array['List'][1]['Value']);
        $this->assertSame(0, $array['List'][2]['Value']);
    }

    public function test_preserves_float_types(): void
    {
        $params = new CustomParameters([
            'price' => 99.99,
            'rate' => 0.15,
            'zero' => 0.0,
        ]);

        $array = $params->toArray();

        $this->assertSame(99.99, $array['List'][0]['Value']);
        $this->assertSame(0.15, $array['List'][1]['Value']);
        $this->assertSame(0.0, $array['List'][2]['Value']);
        $this->assertIsFloat($array['List'][2]['Value']);
    }

    public function test_preserves_null_values_in_list(): void
    {
        $params = new CustomParameters([
            'optional' => null,
            'another' => null,
        ]);

        $array = $params->toArray();

        $this->assertCount(2, $array['List']);
        $this->assertNull($array['List'][0]['Value']);
        $this->assertNull($array['List'][1]['Value']);
        $this->assertSame('optional', $array['List'][0]['Name']);
        $this->assertSame('another', $array['List'][1]['Name']);
    }

    public function test_preserves_zero_values(): void
    {
        $params = new CustomParameters([
            'intZero' => 0,
            'floatZero' => 0.0,
            'stringZero' => '0',
            'emptyString' => '',
        ]);

        $array = $params->toArray();

        $this->assertCount(4, $array['List']);
        $this->assertSame(0, $array['List'][0]['Value']);
        $this->assertSame(0.0, $array['List'][1]['Value']);
        $this->assertSame('0', $array['List'][2]['Value']);
        $this->assertSame('', $array['List'][3]['Value']);
    }

    public function test_preserves_empty_string_values(): void
    {
        $params = new CustomParameters([
            'empty' => '',
            'whitespace' => '   ',
            'tab' => "\t",
        ]);

        $array = $params->toArray();

        $this->assertSame('', $array['List'][0]['Value']);
        $this->assertSame('   ', $array['List'][1]['Value']);
        $this->assertSame("\t", $array['List'][2]['Value']);
    }

    public function test_handles_unicode_values(): void
    {
        $params = new CustomParameters([
            'emoji' => '🎉💳',
            'chinese' => '支付宝',
            'arabic' => 'الدفع',
            'currency' => '€ £ ¥',
        ]);

        $array = $params->toArray();

        $this->assertSame('🎉💳', $array['List'][0]['Value']);
        $this->assertSame('支付宝', $array['List'][1]['Value']);
        $this->assertSame('الدفع', $array['List'][2]['Value']);
        $this->assertSame('€ £ ¥', $array['List'][3]['Value']);
    }

    public function test_handles_special_characters_in_keys(): void
    {
        $params = new CustomParameters([
            'key-with-dash' => 'value1',
            'key.with.dot' => 'value2',
            'key_with_underscore' => 'value3',
            'key:with:colon' => 'value4',
        ]);

        $array = $params->toArray();

        $keys = array_column($array['List'], 'Name');

        $this->assertContains('key-with-dash', $keys);
        $this->assertContains('key.with.dot', $keys);
        $this->assertContains('key_with_underscore', $keys);
        $this->assertContains('key:with:colon', $keys);
    }

    public function test_handles_numeric_string_keys(): void
    {
        $params = new CustomParameters([
            '0' => 'zero',
            '1' => 'one',
            '10' => 'ten',
        ]);

        $array = $params->toArray();

        $this->assertCount(3, $array['List']);
        // PHP converts numeric string keys to integers automatically
        $this->assertSame(0, $array['List'][0]['Name']);
        $this->assertSame(1, $array['List'][1]['Name']);
        $this->assertSame(10, $array['List'][2]['Name']);
    }

    public function test_handles_large_parameter_sets(): void
    {
        $largeSet = [];
        for ($i = 0; $i < 100; $i++) {
            $largeSet['param' . $i] = 'value' . $i;
        }

        $params = new CustomParameters($largeSet);
        $array = $params->toArray();

        $this->assertCount(100, $array['List']);
        $this->assertSame('param0', $array['List'][0]['Name']);
        $this->assertSame('value0', $array['List'][0]['Value']);
        $this->assertSame('param99', $array['List'][99]['Name']);
        $this->assertSame('value99', $array['List'][99]['Value']);
    }

    public function test_magic_get_access_to_list(): void
    {
        $params = new CustomParameters([
            'key1' => 'value1',
            'key2' => 'value2',
        ]);

        $list = $params->List;

        $this->assertIsArray($list);
        $this->assertCount(2, $list);
        $this->assertSame('key1', $list[0]['Name']);
        $this->assertSame('value1', $list[0]['Value']);
    }

    public function test_get_object_vars_includes_list(): void
    {
        $params = new CustomParameters([
            'key1' => 'value1',
        ]);

        $vars = $params->getObjectVars();

        $this->assertArrayHasKey('List', $vars);
        $this->assertIsArray($vars['List']);
        $this->assertCount(1, $vars['List']);
    }

    public function test_preserves_mixed_types_in_single_call(): void
    {
        $params = new CustomParameters([
            'string' => 'text',
            'int' => 42,
            'float' => 3.14,
            'bool' => true,
            'null' => null,
            'zero' => 0,
            'empty' => '',
        ]);

        $array = $params->toArray();

        $this->assertCount(7, $array['List']);
        $this->assertIsString($array['List'][0]['Value']);
        $this->assertIsInt($array['List'][1]['Value']);
        $this->assertIsFloat($array['List'][2]['Value']);
        $this->assertIsBool($array['List'][3]['Value']);
        $this->assertNull($array['List'][4]['Value']);
        $this->assertIsInt($array['List'][5]['Value']);
        $this->assertIsString($array['List'][6]['Value']);
    }

    public function test_handles_very_long_string_values(): void
    {
        $longString = str_repeat('A', 10000);
        $params = new CustomParameters(['longKey' => $longString]);

        $array = $params->toArray();

        $this->assertSame($longString, $array['List'][0]['Value']);
        $this->assertSame(10000, strlen($array['List'][0]['Value']));
    }

    public function test_duplicate_keys_in_multiple_calls_create_duplicate_entries(): void
    {
        $params = new CustomParameters(['key' => 'value1']);
        $params->setProperties(['key' => 'value2']);

        $array = $params->toArray();

        $this->assertCount(2, $array['List']);
        $this->assertSame('key', $array['List'][0]['Name']);
        $this->assertSame('value1', $array['List'][0]['Value']);
        $this->assertSame('key', $array['List'][1]['Name']);
        $this->assertSame('value2', $array['List'][1]['Value']);
    }
}
