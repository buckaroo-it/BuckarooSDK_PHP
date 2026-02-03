<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Tests\TestCase;

class ServiceParameterTest extends TestCase
{
    protected function makeTestable(array $data = []): \Buckaroo\Models\ServiceParameter
    {
        return new class($data) extends \Buckaroo\Models\ServiceParameter {
            public $methodWasCalled;
            public $methodWasInvoked = false;
            public $regularProperty;
            public $anotherProperty;
            public $returnedValue;

            protected array $groupData = [
                'articles' => [
                    'groupType' => 'Article',
                ],
                'billing' => [
                    'groupType' => 'Billing',
                    'groupKey' => 1,
                ],
                'shipping' => [
                    'groupType' => 'Shipping',
                    'groupKey' => 2,
                ],
            ];

            public function testMethod($value)
            {
                $this->methodWasCalled = $value;
                $this->methodWasInvoked = true;
            }

            public function methodWithReturn($value)
            {
                $this->returnedValue = 'RETURNED: ' . $value;

                return $this->returnedValue;
            }
        };
    }

    public function test_invokes_method_when_property_name_matches_method(): void
    {
        $parameter = $this->makeTestable();

        $parameter->setProperties([
            'testMethod' => 'method-value',
        ]);

        $this->assertSame('method-value', $parameter->methodWasCalled);
        $this->assertNull($parameter->testMethod);
    }

    public function test_sets_property_directly_when_no_method_exists(): void
    {
        $parameter = $this->makeTestable();

        $parameter->setProperties([
            'regularProperty' => 'property-value',
        ]);

        $this->assertSame('property-value', $parameter->regularProperty);
        $this->assertNull($parameter->methodWasCalled);
    }

    public function test_handles_mixed_method_invocation_and_property_assignment(): void
    {
        $parameter = $this->makeTestable();

        $parameter->setProperties([
            'testMethod' => 'method-value',
            'regularProperty' => 'property-value',
            'anotherProperty' => 'another-value',
        ]);

        $this->assertSame('method-value', $parameter->methodWasCalled);
        $this->assertSame('property-value', $parameter->regularProperty);
        $this->assertSame('another-value', $parameter->anotherProperty);
    }

    public function test_invokes_method_with_null_value(): void
    {
        $parameter = $this->makeTestable();

        $parameter->setProperties([
            'testMethod' => null,
        ]);

        $this->assertNull($parameter->methodWasCalled);
        $this->assertTrue($parameter->methodWasInvoked);
    }

    public function test_returns_self_from_set_properties(): void
    {
        $parameter = $this->makeTestable();

        $result = $parameter->setProperties([
            'regularProperty' => 'value',
        ]);

        $this->assertSame($parameter, $result);
    }

    public function test_handles_empty_array_in_set_properties(): void
    {
        $parameter = $this->makeTestable();
        $parameter->setProperties(['regularProperty' => 'initial']);

        $parameter->setProperties([]);

        $this->assertSame('initial', $parameter->regularProperty);
    }

    public function test_handles_null_in_set_properties(): void
    {
        $parameter = $this->makeTestable();
        $parameter->setProperties(['regularProperty' => 'initial']);

        $parameter->setProperties(null);

        $this->assertSame('initial', $parameter->regularProperty);
    }

    public function test_uses_set_properties_in_constructor(): void
    {
        $parameter = $this->makeTestable([
            'testMethod' => 'constructor-method-value',
            'regularProperty' => 'constructor-property-value',
        ]);

        $this->assertSame('constructor-method-value', $parameter->methodWasCalled);
        $this->assertSame('constructor-property-value', $parameter->regularProperty);
    }

    public function test_get_group_type_returns_correct_value(): void
    {
        $parameter = $this->makeTestable();

        $groupType = $parameter->getGroupType('articles');

        $this->assertSame('Article', $groupType);
    }

    public function test_get_group_type_returns_null_for_missing_key(): void
    {
        $parameter = $this->makeTestable();

        $groupType = $parameter->getGroupType('nonExistentKey');

        $this->assertNull($groupType);
    }

    public function test_get_group_key_returns_correct_value(): void
    {
        $parameter = $this->makeTestable();

        $groupKey = $parameter->getGroupKey('billing');

        $this->assertSame(1, $groupKey);
    }

    public function test_get_group_key_returns_null_for_missing_key(): void
    {
        $parameter = $this->makeTestable();

        $groupKey = $parameter->getGroupKey('nonExistentKey');

        $this->assertNull($groupKey);
    }

    public function test_group_data_included_in_to_array(): void
    {
        $parameter = $this->makeTestable([
            'regularProperty' => 'value',
        ]);

        $array = $parameter->toArray();

        $this->assertArrayHasKey('groupData', $array);
        $this->assertIsArray($array['groupData']);
        $this->assertArrayHasKey('articles', $array['groupData']);
        $this->assertSame('Article', $array['groupData']['articles']['groupType']);
        $this->assertArrayHasKey('billing', $array['groupData']);
        $this->assertSame('Billing', $array['groupData']['billing']['groupType']);
        $this->assertSame(1, $array['groupData']['billing']['groupKey']);
    }

    public function test_handles_multiple_group_data_entries(): void
    {
        $parameter = $this->makeTestable();

        $this->assertSame('Article', $parameter->getGroupType('articles'));
        $this->assertNull($parameter->getGroupKey('articles'));
        $this->assertSame('Billing', $parameter->getGroupType('billing'));
        $this->assertSame(1, $parameter->getGroupKey('billing'));
        $this->assertSame('Shipping', $parameter->getGroupType('shipping'));
        $this->assertSame(2, $parameter->getGroupKey('shipping'));
    }

    public function test_method_invocation_can_return_values(): void
    {
        $parameter = $this->makeTestable();

        $parameter->setProperties([
            'methodWithReturn' => 'input',
        ]);

        $this->assertSame('RETURNED: input', $parameter->returnedValue);
    }

    public function test_get_group_type_with_empty_string_key(): void
    {
        $parameter = $this->makeTestable();

        $groupType = $parameter->getGroupType('');

        $this->assertNull($groupType);
    }

    public function test_get_group_key_with_empty_string_key(): void
    {
        $parameter = $this->makeTestable();

        $groupKey = $parameter->getGroupKey('');

        $this->assertNull($groupKey);
    }
}
