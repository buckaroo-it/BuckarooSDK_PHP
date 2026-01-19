<?php

declare(strict_types=1);

namespace Tests\Unit\Transaction\Request\HttpClient;

use Buckaroo\Exceptions\BuckarooException;
use Buckaroo\Handlers\Logging\DefaultLogger;
use Buckaroo\Handlers\Logging\Subject;
use Buckaroo\Transaction\Request\HttpClient\HttpClientAbstract;
use Buckaroo\Transaction\Request\HttpClient\HttpClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class HttpClientAbstractTest extends TestCase
{
    private HttpClientAbstract $httpClient;
    private Subject $logger;

    protected function setUp(): void
    {
        parent::setUp();

        // Use DefaultLogger which implements both Subject and LoggerInterface
        $this->logger = new DefaultLogger();

        // Create a concrete implementation of the abstract class for testing
        $this->httpClient = new class($this->logger) extends HttpClientAbstract {
            public function call(string $url, array $headers, string $method, ?string $data = null)
            {
                return null;
            }

            // Expose protected methods for testing
            public function testConvertHeadersFormat(array $headers): array
            {
                return $this->convertHeadersFormat($headers);
            }

            public function testGetDecodedResult($response, $result): array
            {
                return $this->getDecodedResult($response, $result);
            }
        };
    }

    public function test_implements_http_client_interface(): void
    {
        $this->assertInstanceOf(HttpClientInterface::class, $this->httpClient);
    }

    public function test_convert_headers_format_converts_string_headers_to_associative_array(): void
    {
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer token123',
            'Accept: application/json',
        ];

        $result = $this->httpClient->testConvertHeadersFormat($headers);

        $this->assertArrayHasKey('Content-Type', $result);
        $this->assertArrayHasKey('Authorization', $result);
        $this->assertArrayHasKey('Accept', $result);
        $this->assertSame('application/json', $result['Content-Type']);
        $this->assertSame('Bearer token123', $result['Authorization']);
        $this->assertSame('application/json', $result['Accept']);
    }

    public function test_convert_headers_format_handles_empty_array(): void
    {
        $result = $this->httpClient->testConvertHeadersFormat([]);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test_convert_headers_format_handles_headers_with_colons_in_value(): void
    {
        $headers = [
            'X-Custom: value:with:colons',
        ];

        $result = $this->httpClient->testConvertHeadersFormat($headers);

        $this->assertSame('value:with:colons', $result['X-Custom']);
    }

    public function test_get_decoded_result_returns_decoded_json(): void
    {
        $response = new Response(200);
        $result = '{"key": "value", "number": 123}';

        $decoded = $this->httpClient->testGetDecodedResult($response, $result);

        $this->assertIsArray($decoded);
        $this->assertSame('value', $decoded['key']);
        $this->assertSame(123, $decoded['number']);
    }

    public function test_get_decoded_result_handles_nested_json(): void
    {
        $response = new Response(200);
        $result = '{"outer": {"inner": {"deep": "value"}}}';

        $decoded = $this->httpClient->testGetDecodedResult($response, $result);

        $this->assertIsArray($decoded);
        $this->assertSame('value', $decoded['outer']['inner']['deep']);
    }

    public function test_get_decoded_result_handles_empty_json_object(): void
    {
        $response = new Response(200);
        $result = '{}';

        $decoded = $this->httpClient->testGetDecodedResult($response, $result);

        $this->assertIsArray($decoded);
        $this->assertEmpty($decoded);
    }

    public function test_get_decoded_result_handles_json_array(): void
    {
        $response = new Response(200);
        $result = '[{"id": 1}, {"id": 2}]';

        $decoded = $this->httpClient->testGetDecodedResult($response, $result);

        $this->assertIsArray($decoded);
        $this->assertCount(2, $decoded);
        $this->assertSame(1, $decoded[0]['id']);
        $this->assertSame(2, $decoded[1]['id']);
    }

    public function test_get_decoded_result_throws_exception_for_invalid_json(): void
    {
        $response = new Response(500);
        $result = 'Invalid JSON response';

        $this->expectException(BuckarooException::class);
        $this->expectExceptionMessage('Status code: 500 Message: Invalid JSON response');

        $this->httpClient->testGetDecodedResult($response, $result);
    }

    public function test_get_decoded_result_throws_exception_for_html_response(): void
    {
        $response = new Response(502);
        $result = '<html><body>Bad Gateway</body></html>';

        $this->expectException(BuckarooException::class);
        $this->expectExceptionMessage('Status code: 502');

        $this->httpClient->testGetDecodedResult($response, $result);
    }

    public function test_get_decoded_result_throws_exception_for_empty_response(): void
    {
        $response = new Response(204);
        $result = '';

        $this->expectException(BuckarooException::class);
        $this->expectExceptionMessage('Status code: 204');

        $this->httpClient->testGetDecodedResult($response, $result);
    }

    public function test_get_decoded_result_throws_exception_for_null_json(): void
    {
        $response = new Response(200);
        $result = 'null';

        $this->expectException(BuckarooException::class);

        $this->httpClient->testGetDecodedResult($response, $result);
    }

    public function test_timeout_constants_are_defined(): void
    {
        // Use reflection to access protected constants
        $reflection = new \ReflectionClass(HttpClientAbstract::class);

        $timeout = $reflection->getConstant('TIMEOUT');
        $connectTimeout = $reflection->getConstant('CONNECT_TIMEOUT');

        $this->assertSame(30, $timeout);
        $this->assertSame(5, $connectTimeout);
    }
}
