<?php

declare(strict_types=1);

namespace Tests\Unit\Transaction\Request;

use Buckaroo\Models\Model;
use Buckaroo\Models\Services;
use Buckaroo\Resources\Arrayable;
use Buckaroo\Transaction\Request\TransactionRequest;
use Tests\TestCase;

class TransactionRequestTest extends TestCase
{
    public function test_initializes_with_client_user_agent(): void
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Test User Agent/1.0';

        $request = new TransactionRequest();
        $data = $request->data();

        $this->assertArrayHasKey('ClientUserAgent', $data);
        $this->assertSame('Test User Agent/1.0', $data['ClientUserAgent']);
    }

    public function test_initializes_with_empty_user_agent_if_not_set(): void
    {
        unset($_SERVER['HTTP_USER_AGENT']);

        $request = new TransactionRequest();
        $data = $request->data();

        $this->assertArrayHasKey('ClientUserAgent', $data);
        $this->assertSame('', $data['ClientUserAgent']);
    }

    public function test_sets_data_with_set_data_method(): void
    {
        $request = new TransactionRequest();
        $request->setData('AmountDebit', 10.50);
        $request->setData('Currency', 'EUR');
        $request->setData('Invoice', 'INV-12345');

        $data = $request->data();

        $this->assertSame(10.50, $data['AmountDebit']);
        $this->assertSame('EUR', $data['Currency']);
        $this->assertSame('INV-12345', $data['Invoice']);
    }

    public function test_returns_this_from_set_data_for_method_chaining(): void
    {
        $request = new TransactionRequest();
        $result = $request->setData('AmountDebit', 10.50);

        $this->assertSame($request, $result);
    }

    public function test_chains_multiple_set_data_calls(): void
    {
        $request = new TransactionRequest();

        $request->setData('AmountDebit', 25.00)
            ->setData('Currency', 'USD')
            ->setData('Invoice', 'TEST-001')
            ->setData('Description', 'Test payment');

        $data = $request->data();

        $this->assertSame(25.00, $data['AmountDebit']);
        $this->assertSame('USD', $data['Currency']);
        $this->assertSame('TEST-001', $data['Invoice']);
        $this->assertSame('Test payment', $data['Description']);
    }

    public function test_overwrites_existing_data_values(): void
    {
        $request = new TransactionRequest();
        $request->setData('AmountDebit', 10.00);
        $request->setData('AmountDebit', 20.00);

        $data = $request->data();

        $this->assertSame(20.00, $data['AmountDebit']);
    }

    public function test_returns_data_array_with_data_method(): void
    {
        $request = new TransactionRequest();
        $request->setData('Key1', 'Value1');
        $request->setData('Key2', 'Value2');

        $data = $request->data();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('Key1', $data);
        $this->assertArrayHasKey('Key2', $data);
    }

    public function test_returns_data_array_with_get_data_method(): void
    {
        $request = new TransactionRequest();
        $request->setData('TestKey', 'TestValue');

        $data = $request->getData();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('TestKey', $data);
        $this->assertSame('TestValue', $data['TestKey']);
    }

    public function test_sets_payload_from_model(): void
    {
        $model = new class extends Model {
            protected string $amountDebit = '15.50';
            protected string $currency = 'EUR';
            protected string $invoice = 'MODEL-123';
        };

        $request = new TransactionRequest();
        $request->setPayload($model);

        $data = $request->data();

        $this->assertSame('15.50', $data['AmountDebit']);
        $this->assertSame('EUR', $data['Currency']);
        $this->assertSame('MODEL-123', $data['Invoice']);
    }

    public function test_returns_this_from_set_payload_for_method_chaining(): void
    {
        $model = new class extends Model {
            protected string $amount = '10.00';
        };

        $request = new TransactionRequest();
        $result = $request->setPayload($model);

        $this->assertSame($request, $result);
    }

    public function test_chains_set_payload_with_set_data(): void
    {
        $model = new class extends Model {
            protected string $amountDebit = '10.00';
        };

        $request = new TransactionRequest();
        $request->setPayload($model)
            ->setData('Currency', 'USD')
            ->setData('Invoice', 'CHAIN-001');

        $data = $request->data();

        $this->assertSame('10.00', $data['AmountDebit']);
        $this->assertSame('USD', $data['Currency']);
        $this->assertSame('CHAIN-001', $data['Invoice']);
    }

    public function test_returns_services_instance(): void
    {
        $request = new TransactionRequest();
        $services = $request->getServices();

        $this->assertInstanceOf(Services::class, $services);
    }

    public function test_lazy_initializes_services(): void
    {
        $request = new TransactionRequest();
        $data = $request->data();

        $this->assertArrayNotHasKey('Services', $data);

        $services = $request->getServices();

        $this->assertInstanceOf(Services::class, $services);
        $this->assertArrayHasKey('Services', $request->data());
    }

    public function test_returns_same_services_instance_on_multiple_calls(): void
    {
        $request = new TransactionRequest();

        $services1 = $request->getServices();
        $services2 = $request->getServices();

        $this->assertSame($services1, $services2);
    }

    public function test_converts_to_array(): void
    {
        $request = new TransactionRequest();
        $request->setData('AmountDebit', 10.00);
        $request->setData('Currency', 'EUR');

        $array = $request->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('AmountDebit', $array);
        $this->assertSame(10.00, $array['AmountDebit']);
        $this->assertSame('EUR', $array['Currency']);
    }

    public function test_converts_arrayable_objects_to_arrays_in_to_array(): void
    {
        $arrayable = new class implements Arrayable {
            public function toArray(): array
            {
                return ['key' => 'value', 'number' => 42];
            }
        };

        $request = new TransactionRequest();
        $request->setData('CustomObject', $arrayable);

        $array = $request->toArray();

        $this->assertIsArray($array['CustomObject']);
        $this->assertSame('value', $array['CustomObject']['key']);
        $this->assertSame(42, $array['CustomObject']['number']);
    }

    public function test_converts_services_to_array_in_to_array(): void
    {
        $request = new TransactionRequest();
        $services = $request->getServices();

        $array = $request->toArray();

        $this->assertIsArray($array['Services']);
    }

    public function test_converts_to_json(): void
    {
        $request = new TransactionRequest();
        $request->setData('AmountDebit', 10.50);
        $request->setData('Currency', 'EUR');

        $json = $request->toJson();

        $this->assertJson($json);

        $decoded = json_decode($json, true);
        $this->assertSame(10.50, $decoded['AmountDebit']);
        $this->assertSame('EUR', $decoded['Currency']);
    }

    public function test_implements_array_access_interface(): void
    {
        $request = new TransactionRequest();

        // Test offsetSet
        $request['AmountDebit'] = 15.00;
        $request['Currency'] = 'USD';

        // Test offsetGet
        $this->assertSame(15.00, $request['AmountDebit']);
        $this->assertSame('USD', $request['Currency']);

        // Test offsetExists
        $this->assertTrue(isset($request['Currency']));
        $this->assertFalse(isset($request['NonExistent']));

        // Test offsetGet for non-existent key
        $this->assertNull($request['NonExistent']);

        // Test offsetUnset
        unset($request['AmountDebit']);
        $this->assertFalse(isset($request['AmountDebit']));
        $this->assertTrue(isset($request['Currency']));
    }

    public function test_implements_json_serializable(): void
    {
        $request = new TransactionRequest();
        $request->setData('AmountDebit', 20.50);
        $request->setData('Currency', 'GBP');

        $json = json_encode($request);

        $this->assertJson($json);

        $decoded = json_decode($json, true);
        $this->assertSame(20.50, $decoded['AmountDebit']);
        $this->assertSame('GBP', $decoded['Currency']);
    }

    public function test_sets_request_header(): void
    {
        $request = new TransactionRequest();
        $request->setHeader('Content-Type', 'application/json');
        $request->setHeader('Authorization', 'Bearer token123');

        $this->assertSame('application/json', $request->getHeader('Content-Type'));
        $this->assertSame('Bearer token123', $request->getHeader('Authorization'));
    }

    public function test_gets_request_header_case_insensitively(): void
    {
        $request = new TransactionRequest();
        $request->setHeader('Content-Type', 'application/json');

        $this->assertSame('application/json', $request->getHeader('content-type'));
        $this->assertSame('application/json', $request->getHeader('CONTENT-TYPE'));
        $this->assertSame('application/json', $request->getHeader('Content-Type'));
    }

    public function test_returns_null_for_non_existent_header(): void
    {
        $request = new TransactionRequest();

        $this->assertNull($request->getHeader('Non-Existent-Header'));
    }

    public function test_handles_complex_nested_data_structures(): void
    {
        $request = new TransactionRequest();
        $request->setData('Services', [
            'Name' => 'ideal',
            'Action' => 'Pay',
            'Parameters' => [
                ['Name' => 'issuer', 'Value' => 'ABNANL2A'],
            ],
        ]);

        $data = $request->data();

        $this->assertIsArray($data['Services']);
        $this->assertSame('ideal', $data['Services']['Name']);
        $this->assertIsArray($data['Services']['Parameters']);
    }

    public function test_supports_all_data_types(): void
    {
        $request = new TransactionRequest();

        // Numeric values
        $request->setData('AmountDebit', 10.50);
        $request->setData('Quantity', 5);

        // Boolean values
        $request->setData('IsTest', true);
        $request->setData('SendInvoice', false);

        // Null values
        $request->setData('OptionalField', null);

        // Array values
        $request->setData('Items', ['item1', 'item2', 'item3']);

        $data = $request->data();

        // Assert numeric values
        $this->assertSame(10.50, $data['AmountDebit']);
        $this->assertSame(5, $data['Quantity']);

        // Assert boolean values
        $this->assertTrue($data['IsTest']);
        $this->assertFalse($data['SendInvoice']);

        // Assert null values
        $this->assertArrayHasKey('OptionalField', $data);
        $this->assertNull($data['OptionalField']);

        // Assert array values
        $this->assertIsArray($data['Items']);
        $this->assertCount(3, $data['Items']);
        $this->assertSame(['item1', 'item2', 'item3'], $data['Items']);
    }

    public function test_handles_empty_data(): void
    {
        $_SERVER['HTTP_USER_AGENT'] = '';
        $request = new TransactionRequest();

        $array = $request->toArray();
        $json = $request->toJson();

        $this->assertIsArray($array);
        $this->assertJson($json);
    }

    public function test_can_build_complete_payment_request(): void
    {
        $request = new TransactionRequest();
        $request->setData('AmountDebit', 50.00)
            ->setData('Currency', 'EUR')
            ->setData('Invoice', 'COMPLETE-001')
            ->setData('Description', 'Complete payment test')
            ->setData('ReturnURL', 'https://example.com/return')
            ->setData('Services', [
                'Name' => 'creditcard',
                'Action' => 'Pay',
            ]);

        $array = $request->toArray();

        $this->assertSame(50.00, $array['AmountDebit']);
        $this->assertSame('EUR', $array['Currency']);
        $this->assertSame('COMPLETE-001', $array['Invoice']);
        $this->assertSame('Complete payment test', $array['Description']);
        $this->assertSame('https://example.com/return', $array['ReturnURL']);
        $this->assertIsArray($array['Services']);
        $this->assertSame('creditcard', $array['Services']['Name']);
    }
}
