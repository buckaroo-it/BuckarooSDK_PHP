<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use Buckaroo\BuckarooClient;
use Buckaroo\Config\DefaultConfig;
use Buckaroo\Exceptions\AuthenticationException;
use Buckaroo\Exceptions\BuckarooApiException;
use Buckaroo\Exceptions\BuckarooException;
use Buckaroo\Exceptions\ParameterValidationException;
use Buckaroo\Exceptions\ReplyValidationException;
use Buckaroo\Exceptions\TransferException;
use Buckaroo\Handlers\HMAC\Validator;
use Buckaroo\Handlers\Logging\DefaultLogger;
use Buckaroo\Handlers\Reply\ReplyHandler;
use Buckaroo\PaymentMethods\In3\Models\Recipient;
use Buckaroo\Services\PayloadService;
use Buckaroo\Transaction\Request\HttpClient\GuzzleHttpClientV7;
use Buckaroo\Transaction\Request\HttpClient\HttpClientAbstract;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ErrorHierarchyTest extends TestCase
{
    /**
     * @dataProvider subclassProvider
     */
    public function test_every_sdk_exception_extends_buckaroo_exception(string $class): void
    {
        $exception = new $class('message');

        $this->assertInstanceOf(BuckarooException::class, $exception);
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function subclassProvider(): array
    {
        return [
            [AuthenticationException::class],
            [ParameterValidationException::class],
            [BuckarooApiException::class],
            [TransferException::class],
            [ReplyValidationException::class],
        ];
    }

    public function test_transfer_exception_is_an_api_exception(): void
    {
        $this->assertInstanceOf(BuckarooApiException::class, new TransferException('message'));
    }

    public function test_standard_constructor_signature(): void
    {
        $previous = new Exception('root');
        $exception = new BuckarooException('Something failed', 42, $previous);

        $this->assertSame('Buckaroo SDKExeption: Something failed', $exception->getMessage());
        $this->assertSame(42, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    /**
     * Logging in the legacy form is covered by BuckarooExceptionTest.
     */
    public function test_legacy_logger_first_constructor_still_works(): void
    {
        $previous = new Exception('root');
        $exception = new TransferException(new DefaultLogger(), 'Legacy message', 7, $previous);

        $this->assertSame('Buckaroo TransferException Legacy message', $exception->getMessage());
        $this->assertSame(7, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());

        $exception = new BuckarooException(null, 'Legacy message', 7, $previous);

        $this->assertSame('Buckaroo SDKExeption: Legacy message', $exception->getMessage());
        $this->assertSame(7, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function test_parameter_name_is_set_by_for_parameter(): void
    {
        $exception = ParameterValidationException::forParameter('name', 'Missing creditcard name');

        $this->assertSame('name', $exception->getParameterName());
        $this->assertNull((new ParameterValidationException('Invalid'))->getParameterName());
    }

    public function test_http_details_are_empty_without_a_response(): void
    {
        $exception = new TransferException('Transfer failed');

        $this->assertNull($exception->getHttpStatus());
        $this->assertNull($exception->getHttpBody());
        $this->assertNull($exception->getJsonBody());
        $this->assertSame([], $exception->getHeaders());
    }

    public function test_http_details_are_exposed(): void
    {
        $exception = (new BuckarooApiException('Bad response'))
            ->withHttpResponse(500, '{"Error":"Server error"}', ['X-Request-Id' => ['abc']]);

        $this->assertSame(500, $exception->getHttpStatus());
        $this->assertSame('{"Error":"Server error"}', $exception->getHttpBody());
        $this->assertSame(['Error' => 'Server error'], $exception->getJsonBody());
        $this->assertSame(['X-Request-Id' => ['abc']], $exception->getHeaders());
    }

    /**
     * @dataProvider nonJsonResponseProvider
     */
    public function test_non_json_response_throws_typed_exception(
        int $status,
        string $body,
        string $expectedClass
    ): void {
        $httpClient = $this->httpClientExposingDecode();
        $response = new Response($status, ['Content-Type' => 'text/plain'], $body);

        try {
            $httpClient->decode($response, $body);
            $this->fail('Expected an exception');
        } catch (BuckarooApiException | AuthenticationException $e) {
            $this->assertSame($expectedClass, get_class($e));
            $this->assertSame($status, $e->getHttpStatus());
            $this->assertSame($body, $e->getHttpBody());
            $this->assertSame(['text/plain'], $e->getHeaders()['Content-Type']);
            $this->assertStringContainsString('Status code: ' . $status, $e->getMessage());
        }
    }

    public function nonJsonResponseProvider(): array
    {
        return [
            'invalid keys (Buckaroo answers 400)' => [
                400,
                'application access is denied, Authorization header value is invalid',
                AuthenticationException::class,
            ],
            'unauthorized' => [401, 'Unauthorized', AuthenticationException::class],
            'forbidden' => [403, 'Forbidden', AuthenticationException::class],
            'server error' => [500, 'Internal Server Error', BuckarooApiException::class],
            'bad gateway' => [502, '<html>Bad Gateway</html>', BuckarooApiException::class],
        ];
    }

    public function test_json_response_is_returned_whatever_the_status(): void
    {
        $body = '{"Status":{"Code":{"Code":491}}}';

        $result = $this->httpClientExposingDecode()->decode(new Response(400), $body);

        $this->assertSame(['Status' => ['Code' => ['Code' => 491]]], $result);
    }

    public function test_connection_failure_throws_transfer_exception_with_previous(): void
    {
        $connectException = new ConnectException('Could not resolve host', new Request('POST', 'test'));
        $client = new GuzzleHttpClientV7(new DefaultConfig('test-key', 'test-secret', 'test'));

        $property = (new ReflectionClass($client))->getProperty('httpClient');
        $property->setAccessible(true);
        $property->setValue($client, new Client(['handler' => HandlerStack::create(new MockHandler([$connectException]))]));

        try {
            $client->call('https://testcheckout.buckaroo.nl/json/Transaction/', [], 'POST', '{}');
            $this->fail('Expected a TransferException');
        } catch (TransferException $e) {
            $this->assertSame($connectException, $e->getPrevious());
            $this->assertStringContainsString('Could not resolve host', $e->getMessage());
            $this->assertNull($e->getHttpStatus());
        }
    }

    public function test_missing_credentials_throw_authentication_exception(): void
    {
        $this->expectException(AuthenticationException::class);

        new BuckarooClient('', '');
    }

    public function test_missing_config_on_transaction_client_throws_authentication_exception(): void
    {
        $client = (new ReflectionClass(\Buckaroo\Transaction\Client::class))->newInstanceWithoutConstructor();

        $logger = (new ReflectionClass($client))->getProperty('logger');
        $logger->setAccessible(true);
        $logger->setValue($client, new DefaultLogger());

        $config = (new ReflectionClass($client))->getProperty('config');
        $config->setAccessible(true);
        $config->setValue($client, null);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('No config has been configured');

        $client->config();
    }

    public function test_unknown_payment_method_throws_parameter_validation_exception(): void
    {
        $buckaroo = new BuckarooClient('test-key', 'test-secret');

        try {
            $buckaroo->method('does-not-exist');
            $this->fail('Expected a ParameterValidationException');
        } catch (ParameterValidationException $e) {
            $this->assertSame('method', $e->getParameterName());
            $this->assertStringContainsString('does-not-exist', $e->getMessage());
        }
    }

    public function test_unknown_action_throws_parameter_validation_exception(): void
    {
        $this->expectException(ParameterValidationException::class);

        (new BuckarooClient('test-key', 'test-secret'))->method('ideal')->doesNotExist([]);
    }

    public function test_invalid_payload_throws_parameter_validation_exception(): void
    {
        $this->expectException(ParameterValidationException::class);

        new PayloadService('not json');
    }

    public function test_unknown_recipient_category_throws_parameter_validation_exception(): void
    {
        try {
            (new Recipient('BillingCustomer'))->recipient(['category' => 'UNKNOWN']);
            $this->fail('Expected a ParameterValidationException');
        } catch (ParameterValidationException $e) {
            $this->assertSame('category', $e->getParameterName());
        }
    }

    public function test_invalid_reply_data_throws_reply_validation_exception(): void
    {
        $this->expectException(ReplyValidationException::class);

        (new ReplyHandler(new DefaultConfig('test-key', 'test-secret'), 'not json'))->validate();
    }

    public function test_unknown_reply_format_throws_reply_validation_exception(): void
    {
        $this->expectException(ReplyValidationException::class);

        (new ReplyHandler(new DefaultConfig('test-key', 'test-secret'), ['foo' => 'bar']))->validate();
    }

    public function test_failed_hmac_check_throws_reply_validation_exception(): void
    {
        $this->expectException(ReplyValidationException::class);

        (new Validator(new DefaultConfig('test-key', 'test-secret')))
            ->validateOrFail('test-key:bad_hash:nonce:1', 'https://example.com', 'POST', []);
    }

    private function httpClientExposingDecode(): HttpClientAbstract
    {
        return new class(new DefaultLogger()) extends HttpClientAbstract {
            public function call(string $url, array $headers, string $method, ?string $data = null)
            {
                return null;
            }

            public function decode($response, $result): array
            {
                return $this->getDecodedResult($response, $result);
            }
        };
    }
}
