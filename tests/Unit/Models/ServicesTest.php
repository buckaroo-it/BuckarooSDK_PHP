<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Buckaroo\Models\Address;
use Buckaroo\Models\ServiceList;
use Buckaroo\Models\Services;
use Tests\TestCase;

class ServicesTest extends TestCase
{
    public function test_initializes_with_empty_service_list(): void
    {
        $services = new Services();

        $this->assertIsArray($services->serviceList());
        $this->assertEmpty($services->serviceList());
    }

    public function test_sets_service_list_via_constructor(): void
    {
        $serviceList1 = new ServiceList('ideal', 2, 'Pay');
        $serviceList2 = new ServiceList('creditcard', 1, 'Authorize');

        $services = new Services([
            'ServiceList' => [$serviceList1, $serviceList2],
        ]);

        $list = $services->serviceList();

        $this->assertCount(2, $list);
        $this->assertSame($serviceList1, $list[0]);
        $this->assertSame($serviceList2, $list[1]);
    }

    public function test_pushServiceList_appends_to_array(): void
    {
        $services = new Services();
        $serviceList = new ServiceList('paypal', 1, 'Pay');

        $services->pushServiceList($serviceList);

        $list = $services->serviceList();

        $this->assertCount(1, $list);
        $this->assertSame($serviceList, $list[0]);
    }

    public function test_pushServiceList_returns_self_for_fluent_interface(): void
    {
        $services = new Services();
        $serviceList = new ServiceList('ideal', 2, 'Pay');

        $result = $services->pushServiceList($serviceList);

        $this->assertSame($services, $result);
    }

    public function test_multiple_pushServiceList_calls_accumulate(): void
    {
        $services = new Services();

        $serviceList1 = new ServiceList('ideal', 2, 'Pay');
        $serviceList2 = new ServiceList('creditcard', 1, 'Authorize');
        $serviceList3 = new ServiceList('paypal', 1, 'Refund');

        $services->pushServiceList($serviceList1);
        $services->pushServiceList($serviceList2);
        $services->pushServiceList($serviceList3);

        $list = $services->serviceList();

        $this->assertCount(3, $list);
        $this->assertSame($serviceList1, $list[0]);
        $this->assertSame($serviceList2, $list[1]);
        $this->assertSame($serviceList3, $list[2]);
    }

    public function test_can_chain_multiple_pushServiceList_calls(): void
    {
        $services = new Services();

        $serviceList1 = new ServiceList('ideal', 2, 'Pay');
        $serviceList2 = new ServiceList('creditcard', 1, 'Authorize');
        $serviceList3 = new ServiceList('sepa', 1, 'Refund');

        $services
            ->pushServiceList($serviceList1)
            ->pushServiceList($serviceList2)
            ->pushServiceList($serviceList3);

        $list = $services->serviceList();

        $this->assertCount(3, $list);
        $this->assertSame($serviceList1, $list[0]);
        $this->assertSame($serviceList2, $list[1]);
        $this->assertSame($serviceList3, $list[2]);
    }

    public function test_serviceList_getter_returns_correct_array(): void
    {
        $services = new Services();

        $serviceList1 = new ServiceList('ideal', 2, 'Pay');
        $serviceList2 = new ServiceList('creditcard', 1, 'Authorize');

        $services->pushServiceList($serviceList1);
        $services->pushServiceList($serviceList2);

        $list = $services->serviceList();

        $this->assertIsArray($list);
        $this->assertCount(2, $list);
        $this->assertInstanceOf(ServiceList::class, $list[0]);
        $this->assertInstanceOf(ServiceList::class, $list[1]);
    }

    public function test_toArray_includes_service_list_property(): void
    {
        $services = new Services();

        $serviceList = new ServiceList('ideal', 2, 'Pay');
        $services->pushServiceList($serviceList);

        $array = $services->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('ServiceList', $array);
        $this->assertIsArray($array['ServiceList']);
        $this->assertCount(1, $array['ServiceList']);
    }

    public function test_toArray_converts_nested_service_list_objects(): void
    {
        $services = new Services();

        $address = new Address([
            'street' => 'Main Street',
            'houseNumber' => '123',
            'city' => 'Amsterdam',
        ]);

        $serviceList1 = new ServiceList('ideal', 2, 'Pay', $address);
        $serviceList2 = new ServiceList('creditcard', 1, 'Authorize');

        $services
            ->pushServiceList($serviceList1)
            ->pushServiceList($serviceList2);

        $array = $services->toArray();

        $this->assertIsArray($array['ServiceList']);
        $this->assertCount(2, $array['ServiceList']);

        $this->assertIsArray($array['ServiceList'][0]);
        $this->assertArrayHasKey('name', $array['ServiceList'][0]);
        $this->assertArrayHasKey('version', $array['ServiceList'][0]);
        $this->assertArrayHasKey('action', $array['ServiceList'][0]);
        $this->assertSame('ideal', $array['ServiceList'][0]['name']);
        $this->assertSame(2, $array['ServiceList'][0]['version']);
        $this->assertSame('Pay', $array['ServiceList'][0]['action']);

        $this->assertIsArray($array['ServiceList'][1]);
        $this->assertSame('creditcard', $array['ServiceList'][1]['name']);
        $this->assertSame(1, $array['ServiceList'][1]['version']);
        $this->assertSame('Authorize', $array['ServiceList'][1]['action']);
    }

    public function test_toArray_with_empty_services(): void
    {
        $services = new Services();

        $array = $services->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('ServiceList', $array);
        $this->assertIsArray($array['ServiceList']);
        $this->assertEmpty($array['ServiceList']);
    }

    public function test_preserves_service_list_order(): void
    {
        $services = new Services();

        $serviceList1 = new ServiceList('first', 1, 'Pay');
        $serviceList2 = new ServiceList('second', 2, 'Authorize');
        $serviceList3 = new ServiceList('third', 3, 'Refund');

        $services
            ->pushServiceList($serviceList1)
            ->pushServiceList($serviceList2)
            ->pushServiceList($serviceList3);

        $list = $services->serviceList();

        $this->assertSame('first', $list[0]->name);
        $this->assertSame('second', $list[1]->name);
        $this->assertSame('third', $list[2]->name);
    }
}
