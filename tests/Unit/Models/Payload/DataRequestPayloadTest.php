<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Payload;

use Buckaroo\Models\AdditionalParameters;
use Buckaroo\Models\ClientIP;
use Buckaroo\Models\CustomParameters;
use Buckaroo\Models\Payload\DataRequestPayload;
use Tests\TestCase;

class DataRequestPayloadTest extends TestCase
{
    public function test_additional_parameters_creates_list_instead_of_additional_parameter(): void
    {
        $payload = new DataRequestPayload([
            'additionalParameters' => ['param1' => 'value1', 'param2' => 'value2'],
        ]);

        $this->assertInstanceOf(AdditionalParameters::class, $payload->additionalParameters);

        $array = $payload->additionalParameters->toArray();
        $this->assertArrayHasKey('List', $array);
        $this->assertArrayHasKey('AdditionalParameter', $array);
        $this->assertNotEmpty($array['List']);
        $this->assertEmpty($array['AdditionalParameter']);
    }

    public function test_to_array_shows_list_populated_additional_parameter_empty(): void
    {
        $payload = new DataRequestPayload([
            'additionalParameters' => ['key1' => 'val1'],
        ]);

        $array = $payload->toArray();

        $this->assertArrayHasKey('additionalParameters', $array);
        $this->assertIsArray($array['additionalParameters']);
        $this->assertArrayHasKey('List', $array['additionalParameters']);
        $this->assertArrayHasKey('AdditionalParameter', $array['additionalParameters']);
        $this->assertNotEmpty($array['additionalParameters']['List']);
        $this->assertEmpty($array['additionalParameters']['AdditionalParameter']);
    }

    public function test_additional_parameters_receives_is_data_request_true_flag(): void
    {
        $payload = new DataRequestPayload([
            'additionalParameters' => ['test' => 'data'],
        ]);

        $additionalParams = $payload->additionalParameters;
        $array = $additionalParams->toArray();

        $this->assertArrayHasKey('List', $array);
        $this->assertCount(1, $array['List']);
        $this->assertSame('test', $array['List'][0]['Name']);
        $this->assertSame('data', $array['List'][0]['Value']);
    }

    public function test_constructor_with_additional_parameters_creates_correct_structure(): void
    {
        $payload = new DataRequestPayload([
            'additionalParameters' => ['param1' => 'value1', 'param2' => 'value2'],
        ]);

        $array = $payload->additionalParameters->toArray();

        $this->assertArrayHasKey('List', $array);
        $this->assertCount(2, $array['List']);

        $names = array_column($array['List'], 'Name');
        $this->assertContains('param1', $names);
        $this->assertContains('param2', $names);
    }

    public function test_set_properties_with_additional_parameters_creates_correct_structure(): void
    {
        $payload = new DataRequestPayload();

        $payload->setProperties([
            'additionalParameters' => ['key1' => 'val1', 'key2' => 'val2'],
        ]);

        $array = $payload->additionalParameters->toArray();

        $this->assertArrayHasKey('List', $array);
        $this->assertCount(2, $array['List']);
    }

    public function test_additional_parameters_works_with_custom_parameters(): void
    {
        $payload = new DataRequestPayload([
            'additionalParameters' => ['additional' => 'data'],
            'customParameters' => ['custom' => 'value'],
        ]);

        $this->assertInstanceOf(AdditionalParameters::class, $payload->additionalParameters);
        $this->assertInstanceOf(CustomParameters::class, $payload->customParameters);

        $additionalArray = $payload->additionalParameters->toArray();
        $this->assertArrayHasKey('List', $additionalArray);
        $this->assertNotEmpty($additionalArray['List']);
        $this->assertEmpty($additionalArray['AdditionalParameter']);

        $customArray = $payload->customParameters->toArray();
        $this->assertArrayHasKey('List', $customArray);
    }

    public function test_additional_parameters_works_with_client_ip(): void
    {
        $payload = new DataRequestPayload([
            'additionalParameters' => ['param' => 'value'],
            'clientIP' => ['address' => '192.168.1.1', 'type' => 0],
        ]);

        $this->assertInstanceOf(AdditionalParameters::class, $payload->additionalParameters);
        $this->assertInstanceOf(ClientIP::class, $payload->clientIP);

        $additionalArray = $payload->additionalParameters->toArray();
        $this->assertArrayHasKey('List', $additionalArray);
    }

    public function test_all_three_nested_objects_together(): void
    {
        $payload = new DataRequestPayload([
            'additionalParameters' => ['add1' => 'val1'],
            'customParameters' => ['custom1' => 'val2'],
            'clientIP' => ['address' => '10.0.0.1', 'type' => 0],
        ]);

        $this->assertInstanceOf(AdditionalParameters::class, $payload->additionalParameters);
        $this->assertInstanceOf(CustomParameters::class, $payload->customParameters);
        $this->assertInstanceOf(ClientIP::class, $payload->clientIP);

        $array = $payload->toArray();
        $this->assertArrayHasKey('additionalParameters', $array);
        $this->assertArrayHasKey('customParameters', $array);
        $this->assertArrayHasKey('clientIP', $array);

        $this->assertArrayHasKey('List', $array['additionalParameters']);
        $this->assertNotEmpty($array['additionalParameters']['List']);
        $this->assertEmpty($array['additionalParameters']['AdditionalParameter']);
    }

    public function test_multiple_additional_parameters_entries_populate_list_array(): void
    {
        $payload = new DataRequestPayload([
            'additionalParameters' => [
                'param1' => 'value1',
                'param2' => 'value2',
                'param3' => 'value3',
            ],
        ]);

        $array = $payload->additionalParameters->toArray();

        $this->assertArrayHasKey('List', $array);
        $this->assertCount(3, $array['List']);

        foreach ($array['List'] as $item) {
            $this->assertArrayHasKey('Name', $item);
            $this->assertArrayHasKey('Value', $item);
        }
    }

    public function test_overwrites_additional_parameters_on_subsequent_set_properties(): void
    {
        $payload = new DataRequestPayload([
            'additionalParameters' => ['first' => 'value1'],
        ]);

        $firstAdditionalParams = $payload->additionalParameters;
        $this->assertInstanceOf(AdditionalParameters::class, $firstAdditionalParams);

        $payload->setProperties([
            'additionalParameters' => ['second' => 'value2'],
        ]);

        $secondAdditionalParams = $payload->additionalParameters;
        $this->assertInstanceOf(AdditionalParameters::class, $secondAdditionalParams);
        $this->assertNotSame($firstAdditionalParams, $secondAdditionalParams);

        $array = $secondAdditionalParams->toArray();
        $names = array_column($array['List'], 'Name');
        $this->assertContains('second', $names);
        $this->assertNotContains('first', $names);
    }

    public function test_returns_self_from_set_properties(): void
    {
        $payload = new DataRequestPayload();

        $result = $payload->setProperties([
            'additionalParameters' => ['key' => 'value'],
            'currency' => 'USD',
        ]);

        $this->assertSame($payload, $result);
    }

    public function test_preserves_flat_properties_when_setting_additional_parameters(): void
    {
        $payload = new DataRequestPayload([
            'currency' => 'EUR',
            'invoice' => 'INV-001',
            'description' => 'Initial',
        ]);

        $payload->setProperties([
            'additionalParameters' => ['param' => 'value'],
        ]);

        $this->assertSame('EUR', $payload->currency);
        $this->assertSame('INV-001', $payload->invoice);
        $this->assertSame('Initial', $payload->description);
        $this->assertInstanceOf(AdditionalParameters::class, $payload->additionalParameters);
    }

    public function test_constructor_with_null_initializes_properly(): void
    {
        $payload = new DataRequestPayload(null);

        $this->assertNull($payload->currency);
        $this->assertNull($payload->invoice);
    }

    public function test_constructor_with_empty_array_initializes_properly(): void
    {
        $payload = new DataRequestPayload([]);

        $this->assertNull($payload->currency);
        $this->assertNull($payload->invoice);
    }

    public function test_nested_objects_are_independent_instances(): void
    {
        $sharedData = ['shared' => 'value'];

        $payload1 = new DataRequestPayload(['additionalParameters' => $sharedData]);
        $payload2 = new DataRequestPayload(['additionalParameters' => $sharedData]);

        $this->assertNotSame($payload1->additionalParameters, $payload2->additionalParameters);
        $this->assertInstanceOf(AdditionalParameters::class, $payload1->additionalParameters);
        $this->assertInstanceOf(AdditionalParameters::class, $payload2->additionalParameters);
    }
}
