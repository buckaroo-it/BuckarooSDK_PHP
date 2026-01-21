<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Buckaroo\Models\AdditionalParameters;
use Tests\TestCase;

class AdditionalParametersTest extends TestCase
{
    public function test_default_mode_uses_additional_parameter_array(): void
    {
        $params = new AdditionalParameters([
            'key1' => 'value1',
            'key2' => 'value2',
        ]);

        $array = $params->toArray();

        $this->assertArrayHasKey('AdditionalParameter', $array);
        $this->assertCount(2, $array['AdditionalParameter']);
        $this->assertEmpty($array['List']);
        $this->assertSame('key1', $array['AdditionalParameter'][0]['Name']);
        $this->assertSame('value1', $array['AdditionalParameter'][0]['Value']);
        $this->assertSame('key2', $array['AdditionalParameter'][1]['Name']);
        $this->assertSame('value2', $array['AdditionalParameter'][1]['Value']);
    }

    public function test_data_request_mode_uses_list_array(): void
    {
        $params = new AdditionalParameters([
            'key1' => 'value1',
            'key2' => 'value2',
        ], true);

        $array = $params->toArray();

        $this->assertArrayHasKey('List', $array);
        $this->assertCount(2, $array['List']);
        $this->assertEmpty($array['AdditionalParameter']);
        $this->assertSame('key1', $array['List'][0]['Name']);
        $this->assertSame('value1', $array['List'][0]['Value']);
        $this->assertSame('key2', $array['List'][1]['Name']);
        $this->assertSame('value2', $array['List'][1]['Value']);
    }

    public function test_constructor_flag_controls_array_selection(): void
    {
        $defaultMode = new AdditionalParameters(['key' => 'value'], false);
        $dataRequestMode = new AdditionalParameters(['key' => 'value'], true);

        $defaultArray = $defaultMode->toArray();
        $dataRequestArray = $dataRequestMode->toArray();

        $this->assertCount(1, $defaultArray['AdditionalParameter']);
        $this->assertEmpty($defaultArray['List']);

        $this->assertCount(1, $dataRequestArray['List']);
        $this->assertEmpty($dataRequestArray['AdditionalParameter']);
    }

    public function test_set_properties_respects_mode_across_multiple_calls(): void
    {
        $defaultParams = new AdditionalParameters(['key1' => 'value1'], false);
        $defaultParams->setProperties(['key2' => 'value2']);

        $dataRequestParams = new AdditionalParameters(['key1' => 'value1'], true);
        $dataRequestParams->setProperties(['key2' => 'value2']);

        $defaultArray = $defaultParams->toArray();
        $dataRequestArray = $dataRequestParams->toArray();

        $this->assertCount(2, $defaultArray['AdditionalParameter']);
        $this->assertEmpty($defaultArray['List']);

        $this->assertCount(2, $dataRequestArray['List']);
        $this->assertEmpty($dataRequestArray['AdditionalParameter']);
    }

    public function test_handles_empty_parameters_in_both_modes(): void
    {
        $defaultMode = new AdditionalParameters([], false);
        $dataRequestMode = new AdditionalParameters([], true);

        $defaultArray = $defaultMode->toArray();
        $dataRequestArray = $dataRequestMode->toArray();

        $this->assertTrue(
            !isset($defaultArray['AdditionalParameter']) || empty($defaultArray['AdditionalParameter']),
            'Empty parameters should result in empty or missing AdditionalParameter'
        );

        $this->assertTrue(
            !isset($dataRequestArray['List']) || empty($dataRequestArray['List']),
            'Empty parameters should result in empty or missing List'
        );
    }

    public function test_handles_null_parameters_in_both_modes(): void
    {
        $defaultMode = new AdditionalParameters(null, false);
        $dataRequestMode = new AdditionalParameters(null, true);

        $defaultArray = $defaultMode->toArray();
        $dataRequestArray = $dataRequestMode->toArray();

        $this->assertTrue(
            !isset($defaultArray['AdditionalParameter']) || empty($defaultArray['AdditionalParameter']),
            'Null parameters should result in empty or missing AdditionalParameter'
        );

        $this->assertTrue(
            !isset($dataRequestArray['List']) || empty($dataRequestArray['List']),
            'Null parameters should result in empty or missing List'
        );
    }

    public function test_multiple_set_properties_calls_accumulate_in_default_mode(): void
    {
        $params = new AdditionalParameters(['key1' => 'value1'], false);
        $params->setProperties(['key2' => 'value2']);
        $params->setProperties(['key3' => 'value3']);

        $array = $params->toArray();

        $this->assertCount(3, $array['AdditionalParameter']);
        $this->assertSame('key1', $array['AdditionalParameter'][0]['Name']);
        $this->assertSame('value1', $array['AdditionalParameter'][0]['Value']);
        $this->assertSame('key2', $array['AdditionalParameter'][1]['Name']);
        $this->assertSame('value2', $array['AdditionalParameter'][1]['Value']);
        $this->assertSame('key3', $array['AdditionalParameter'][2]['Name']);
        $this->assertSame('value3', $array['AdditionalParameter'][2]['Value']);
    }

    public function test_multiple_set_properties_calls_accumulate_in_data_request_mode(): void
    {
        $params = new AdditionalParameters(['key1' => 'value1'], true);
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

    public function test_preserves_boolean_types_in_both_modes(): void
    {
        $defaultParams = new AdditionalParameters(['isActive' => true, 'isDeleted' => false], false);
        $dataRequestParams = new AdditionalParameters(['isActive' => true, 'isDeleted' => false], true);

        $defaultArray = $defaultParams->toArray();
        $dataRequestArray = $dataRequestParams->toArray();

        $this->assertSame(true, $defaultArray['AdditionalParameter'][0]['Value']);
        $this->assertSame(false, $defaultArray['AdditionalParameter'][1]['Value']);

        $this->assertSame(true, $dataRequestArray['List'][0]['Value']);
        $this->assertSame(false, $dataRequestArray['List'][1]['Value']);
    }

    public function test_preserves_integer_types_in_both_modes(): void
    {
        $defaultParams = new AdditionalParameters(['count' => 42, 'negative' => -100, 'zero' => 0], false);
        $dataRequestParams = new AdditionalParameters(['count' => 42, 'negative' => -100, 'zero' => 0], true);

        $defaultArray = $defaultParams->toArray();
        $dataRequestArray = $dataRequestParams->toArray();

        $this->assertSame(42, $defaultArray['AdditionalParameter'][0]['Value']);
        $this->assertSame(-100, $defaultArray['AdditionalParameter'][1]['Value']);
        $this->assertSame(0, $defaultArray['AdditionalParameter'][2]['Value']);

        $this->assertSame(42, $dataRequestArray['List'][0]['Value']);
        $this->assertSame(-100, $dataRequestArray['List'][1]['Value']);
        $this->assertSame(0, $dataRequestArray['List'][2]['Value']);
    }

    public function test_preserves_float_types_in_both_modes(): void
    {
        $defaultParams = new AdditionalParameters(['price' => 99.99, 'rate' => 0.15, 'zero' => 0.0], false);
        $dataRequestParams = new AdditionalParameters(['price' => 99.99, 'rate' => 0.15, 'zero' => 0.0], true);

        $defaultArray = $defaultParams->toArray();
        $dataRequestArray = $dataRequestParams->toArray();

        $this->assertSame(99.99, $defaultArray['AdditionalParameter'][0]['Value']);
        $this->assertSame(0.15, $defaultArray['AdditionalParameter'][1]['Value']);
        $this->assertSame(0.0, $defaultArray['AdditionalParameter'][2]['Value']);
        $this->assertIsFloat($defaultArray['AdditionalParameter'][2]['Value']);

        $this->assertSame(99.99, $dataRequestArray['List'][0]['Value']);
        $this->assertSame(0.15, $dataRequestArray['List'][1]['Value']);
        $this->assertSame(0.0, $dataRequestArray['List'][2]['Value']);
        $this->assertIsFloat($dataRequestArray['List'][2]['Value']);
    }

    public function test_preserves_string_types_in_both_modes(): void
    {
        $defaultParams = new AdditionalParameters(['text' => 'hello', 'number' => '123'], false);
        $dataRequestParams = new AdditionalParameters(['text' => 'hello', 'number' => '123'], true);

        $defaultArray = $defaultParams->toArray();
        $dataRequestArray = $dataRequestParams->toArray();

        $this->assertSame('hello', $defaultArray['AdditionalParameter'][0]['Value']);
        $this->assertSame('123', $defaultArray['AdditionalParameter'][1]['Value']);

        $this->assertSame('hello', $dataRequestArray['List'][0]['Value']);
        $this->assertSame('123', $dataRequestArray['List'][1]['Value']);
    }

    public function test_preserves_null_values_in_both_modes(): void
    {
        $defaultParams = new AdditionalParameters(['optional' => null, 'another' => null], false);
        $dataRequestParams = new AdditionalParameters(['optional' => null, 'another' => null], true);

        $defaultArray = $defaultParams->toArray();
        $dataRequestArray = $dataRequestParams->toArray();

        $this->assertCount(2, $defaultArray['AdditionalParameter']);
        $this->assertNull($defaultArray['AdditionalParameter'][0]['Value']);
        $this->assertNull($defaultArray['AdditionalParameter'][1]['Value']);
        $this->assertSame('optional', $defaultArray['AdditionalParameter'][0]['Name']);
        $this->assertSame('another', $defaultArray['AdditionalParameter'][1]['Name']);

        $this->assertCount(2, $dataRequestArray['List']);
        $this->assertNull($dataRequestArray['List'][0]['Value']);
        $this->assertNull($dataRequestArray['List'][1]['Value']);
        $this->assertSame('optional', $dataRequestArray['List'][0]['Name']);
        $this->assertSame('another', $dataRequestArray['List'][1]['Name']);
    }

    public function test_preserves_zero_values(): void
    {
        $params = new AdditionalParameters([
            'intZero' => 0,
            'floatZero' => 0.0,
            'stringZero' => '0',
            'emptyString' => '',
        ]);

        $array = $params->toArray();

        $this->assertCount(4, $array['AdditionalParameter']);
        $this->assertSame(0, $array['AdditionalParameter'][0]['Value']);
        $this->assertSame(0.0, $array['AdditionalParameter'][1]['Value']);
        $this->assertSame('0', $array['AdditionalParameter'][2]['Value']);
        $this->assertSame('', $array['AdditionalParameter'][3]['Value']);
    }

    public function test_preserves_empty_string_values(): void
    {
        $params = new AdditionalParameters([
            'empty' => '',
            'whitespace' => '   ',
            'tab' => "\t",
        ]);

        $array = $params->toArray();

        $this->assertSame('', $array['AdditionalParameter'][0]['Value']);
        $this->assertSame('   ', $array['AdditionalParameter'][1]['Value']);
        $this->assertSame("\t", $array['AdditionalParameter'][2]['Value']);
    }

    public function test_handles_single_parameter(): void
    {
        $defaultParams = new AdditionalParameters(['singleKey' => 'singleValue'], false);
        $dataRequestParams = new AdditionalParameters(['singleKey' => 'singleValue'], true);

        $defaultArray = $defaultParams->toArray();
        $dataRequestArray = $dataRequestParams->toArray();

        $this->assertCount(1, $defaultArray['AdditionalParameter']);
        $this->assertSame('singleKey', $defaultArray['AdditionalParameter'][0]['Name']);
        $this->assertSame('singleValue', $defaultArray['AdditionalParameter'][0]['Value']);

        $this->assertCount(1, $dataRequestArray['List']);
        $this->assertSame('singleKey', $dataRequestArray['List'][0]['Name']);
        $this->assertSame('singleValue', $dataRequestArray['List'][0]['Value']);
    }

    public function test_handles_large_parameter_sets(): void
    {
        $largeSet = [];
        for ($i = 0; $i < 100; $i++) {
            $largeSet['param' . $i] = 'value' . $i;
        }

        $params = new AdditionalParameters($largeSet);
        $array = $params->toArray();

        $this->assertCount(100, $array['AdditionalParameter']);
        $this->assertSame('param0', $array['AdditionalParameter'][0]['Name']);
        $this->assertSame('value0', $array['AdditionalParameter'][0]['Value']);
        $this->assertSame('param99', $array['AdditionalParameter'][99]['Name']);
        $this->assertSame('value99', $array['AdditionalParameter'][99]['Value']);
    }

    public function test_handles_very_long_string_values(): void
    {
        $longString = str_repeat('A', 10000);
        $params = new AdditionalParameters(['longKey' => $longString]);

        $array = $params->toArray();

        $this->assertSame($longString, $array['AdditionalParameter'][0]['Value']);
        $this->assertSame(10000, strlen($array['AdditionalParameter'][0]['Value']));
    }

    public function test_duplicate_keys_create_duplicate_entries(): void
    {
        $defaultParams = new AdditionalParameters(['key' => 'value1'], false);
        $defaultParams->setProperties(['key' => 'value2']);

        $dataRequestParams = new AdditionalParameters(['key' => 'value1'], true);
        $dataRequestParams->setProperties(['key' => 'value2']);

        $defaultArray = $defaultParams->toArray();
        $dataRequestArray = $dataRequestParams->toArray();

        $this->assertCount(2, $defaultArray['AdditionalParameter']);
        $this->assertSame('key', $defaultArray['AdditionalParameter'][0]['Name']);
        $this->assertSame('value1', $defaultArray['AdditionalParameter'][0]['Value']);
        $this->assertSame('key', $defaultArray['AdditionalParameter'][1]['Name']);
        $this->assertSame('value2', $defaultArray['AdditionalParameter'][1]['Value']);

        $this->assertCount(2, $dataRequestArray['List']);
        $this->assertSame('key', $dataRequestArray['List'][0]['Name']);
        $this->assertSame('value1', $dataRequestArray['List'][0]['Value']);
        $this->assertSame('key', $dataRequestArray['List'][1]['Name']);
        $this->assertSame('value2', $dataRequestArray['List'][1]['Value']);
    }

    public function test_handles_special_characters_in_values(): void
    {
        $params = new AdditionalParameters([
            'description' => 'Test & Payment <special>',
            'reference' => 'REF/123/456',
        ]);

        $array = $params->toArray();

        $this->assertSame('Test & Payment <special>', $array['AdditionalParameter'][0]['Value']);
        $this->assertSame('REF/123/456', $array['AdditionalParameter'][1]['Value']);
    }

    public function test_handles_unicode_values(): void
    {
        $params = new AdditionalParameters([
            'emoji' => '🎉💳',
            'chinese' => '支付宝',
            'arabic' => 'الدفع',
            'currency' => '€ £ ¥',
        ]);

        $array = $params->toArray();

        $this->assertSame('🎉💳', $array['AdditionalParameter'][0]['Value']);
        $this->assertSame('支付宝', $array['AdditionalParameter'][1]['Value']);
        $this->assertSame('الدفع', $array['AdditionalParameter'][2]['Value']);
        $this->assertSame('€ £ ¥', $array['AdditionalParameter'][3]['Value']);
    }

    public function test_handles_special_characters_in_keys(): void
    {
        $params = new AdditionalParameters([
            'key-with-dash' => 'value1',
            'key.with.dot' => 'value2',
            'key_with_underscore' => 'value3',
            'key:with:colon' => 'value4',
        ]);

        $array = $params->toArray();

        $keys = array_column($array['AdditionalParameter'], 'Name');

        $this->assertContains('key-with-dash', $keys);
        $this->assertContains('key.with.dot', $keys);
        $this->assertContains('key_with_underscore', $keys);
        $this->assertContains('key:with:colon', $keys);
    }

    public function test_preserves_original_key_names(): void
    {
        $params = new AdditionalParameters([
            'CamelCaseKey' => 'value1',
            'snake_case_key' => 'value2',
            'mixedCase_Key' => 'value3',
        ]);

        $array = $params->toArray();

        $keys = array_column($array['AdditionalParameter'], 'Name');

        $this->assertContains('CamelCaseKey', $keys);
        $this->assertContains('snake_case_key', $keys);
        $this->assertContains('mixedCase_Key', $keys);
    }

    public function test_magic_get_access_to_arrays(): void
    {
        $defaultParams = new AdditionalParameters(['key1' => 'value1', 'key2' => 'value2'], false);
        $dataRequestParams = new AdditionalParameters(['key1' => 'value1', 'key2' => 'value2'], true);

        $additionalParameter = $defaultParams->AdditionalParameter;
        $list = $dataRequestParams->List;

        $this->assertIsArray($additionalParameter);
        $this->assertCount(2, $additionalParameter);
        $this->assertSame('key1', $additionalParameter[0]['Name']);
        $this->assertSame('value1', $additionalParameter[0]['Value']);

        $this->assertIsArray($list);
        $this->assertCount(2, $list);
        $this->assertSame('key1', $list[0]['Name']);
        $this->assertSame('value1', $list[0]['Value']);
    }

    public function test_get_object_vars_includes_correct_array(): void
    {
        $defaultParams = new AdditionalParameters(['key1' => 'value1'], false);
        $dataRequestParams = new AdditionalParameters(['key1' => 'value1'], true);

        $defaultVars = $defaultParams->getObjectVars();
        $dataRequestVars = $dataRequestParams->getObjectVars();

        $this->assertArrayHasKey('AdditionalParameter', $defaultVars);
        $this->assertIsArray($defaultVars['AdditionalParameter']);
        $this->assertCount(1, $defaultVars['AdditionalParameter']);

        $this->assertArrayHasKey('List', $dataRequestVars);
        $this->assertIsArray($dataRequestVars['List']);
        $this->assertCount(1, $dataRequestVars['List']);
    }

    public function test_to_array_structure_in_default_mode(): void
    {
        $params = new AdditionalParameters([
            'key1' => 'value1',
            'key2' => 'value2',
        ], false);

        $array = $params->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('AdditionalParameter', $array);
        $this->assertIsArray($array['AdditionalParameter']);
        $this->assertCount(2, $array['AdditionalParameter']);
        $this->assertEmpty($array['List']);

        foreach ($array['AdditionalParameter'] as $item) {
            $this->assertArrayHasKey('Name', $item);
            $this->assertArrayHasKey('Value', $item);
            $this->assertCount(2, $item);
        }
    }

    public function test_to_array_structure_in_data_request_mode(): void
    {
        $params = new AdditionalParameters([
            'key1' => 'value1',
            'key2' => 'value2',
        ], true);

        $array = $params->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('List', $array);
        $this->assertIsArray($array['List']);
        $this->assertCount(2, $array['List']);
        $this->assertEmpty($array['AdditionalParameter']);

        foreach ($array['List'] as $item) {
            $this->assertArrayHasKey('Name', $item);
            $this->assertArrayHasKey('Value', $item);
            $this->assertCount(2, $item);
        }
    }
}
