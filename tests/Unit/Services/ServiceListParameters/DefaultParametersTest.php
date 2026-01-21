<?php

declare(strict_types=1);

namespace Tests\Unit\Services\ServiceListParameters;

use Buckaroo\Models\ServiceList;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Services\ServiceListParameters\ServiceListParameter;
use Tests\TestCase;

class DefaultParametersTest extends TestCase
{
    public function test_implements_service_list_parameter(): void
    {
        $serviceList = new ServiceList('TestService', 1, 'Pay');
        $defaultParams = new DefaultParameters($serviceList);

        $this->assertInstanceOf(ServiceListParameter::class, $defaultParams);
    }

    public function test_returns_service_list_from_data_method(): void
    {
        $serviceList = new ServiceList('TestService', 1, 'Pay');
        $defaultParams = new DefaultParameters($serviceList);

        $result = $defaultParams->data();

        $this->assertSame($serviceList, $result);
    }

    public function test_service_list_has_empty_parameters_initially(): void
    {
        $serviceList = new ServiceList('TestService', 1, 'Pay');
        $defaultParams = new DefaultParameters($serviceList);

        $result = $defaultParams->data();

        $this->assertEmpty($result->parameters());
    }

    public function test_stores_service_list_correctly(): void
    {
        $serviceList = new ServiceList('CreditCard', 2, 'Authorize');
        $defaultParams = new DefaultParameters($serviceList);

        $result = $defaultParams->data();

        $this->assertInstanceOf(ServiceList::class, $result);
        $this->assertSame('CreditCard', $result->name);
        $this->assertSame(2, $result->version);
        $this->assertSame('Authorize', $result->action);
    }

    public function test_can_be_used_as_base_for_decorator_pattern(): void
    {
        $serviceList = new ServiceList('iDEAL', 1, 'Pay');
        $defaultParams = new DefaultParameters($serviceList);

        // DefaultParameters acts as the base in the decorator pattern
        $data = $defaultParams->data();

        $this->assertInstanceOf(ServiceList::class, $data);
    }

    public function test_handles_different_service_names(): void
    {
        $services = [
            ['name' => 'CreditCard', 'version' => 1, 'action' => 'Pay'],
            ['name' => 'iDEAL', 'version' => 2, 'action' => 'Refund'],
            ['name' => 'PayPal', 'version' => 1, 'action' => 'Authorize'],
            ['name' => 'Bancontact', 'version' => 1, 'action' => 'Capture'],
        ];

        foreach ($services as $service) {
            $serviceList = new ServiceList($service['name'], $service['version'], $service['action']);
            $defaultParams = new DefaultParameters($serviceList);

            $result = $defaultParams->data();

            $this->assertSame($service['name'], $result->name);
            $this->assertSame($service['version'], $result->version);
            $this->assertSame($service['action'], $result->action);
        }
    }
}
