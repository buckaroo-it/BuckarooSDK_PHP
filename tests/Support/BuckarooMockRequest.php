<?php

declare(strict_types=1);

namespace Tests\Support;

use JsonException;

/**
 * Single expected request + canned response
 * Keep it tiny: match by method, url (exact, wildcard, or regex), optional body and headers
 */
final class BuckarooMockRequest
{
    private string $method;
    private string $url; // exact, wildcard like /url/*, or regex like /.../"
    private ?array $jsonExact = null;
    private ?array $jsonSubset = null;
    private ?string $rawEquals = null;

    /** list of "Header-Name: value" substrings to be present in the actual headers */
    private array $headersContain = [];

    private int $status = 200;
    private array $responseHeaders = [];

    /** @var array|string */
    private $responseBody;

    private bool $isJson = true;

    private function __construct(string $method, string $url)
    {
        $this->method = strtoupper($method);
        $this->url = $url;
    }

    /** factories */
    public static function json(string $method, string $url, array $payload, int $status = 200, array $headers = []): self
    {
        $i = new self($method, $url);
        $i->isJson = true;
        $i->responseBody = $payload;
        $i->status = $status;
        $i->responseHeaders = $headers;

        return $i;
    }

    public static function raw(string $method, string $url, string $body, int $status = 200, array $headers = []): self
    {
        $i = new self($method, $url);
        $i->isJson = false;
        $i->responseBody = $body;
        $i->status = $status;
        $i->responseHeaders = $headers;

        return $i;
    }

    /** matchers */
    public function expectJsonExact(array $payload): self
    {
        $this->jsonExact = $payload;

        return $this;
    }

    public function expectJsonSubset(array $subset): self
    {
        $this->jsonSubset = $subset;

        return $this;
    }

    public function expectRawEquals(string $raw): self
    {
        $this->rawEquals = $raw;

        return $this;
    }

    public function expectHeadersContain(string ...$lines): self
    {
        $this->headersContain = $lines;

        return $this;
    }

    /** internal api */
    public function matches(string $method, string $url, array $headers, ?string $raw): bool
    {
        if (strtoupper($method) !== $this->method) {
            return false;
        }

        $isRegex = strlen($this->url) > 2 && $this->url[0] === '/' && substr($this->url, -1) === '/';
        $hasWildcard = !$isRegex && strpos($this->url, '*') !== false;

        if ($hasWildcard) {
            $urlMatches = preg_match('/^' . str_replace('\*', '.*', preg_quote($this->url, '/')) . '$/', $url);
        } elseif ($isRegex) {
            $urlMatches = preg_match($this->url, $url);
        } else {
            $urlMatches = $url === $this->url;
        }

        if (!$urlMatches) {
            return false;
        }

        if ($this->headersContain !== []) {
            $flatHeaders = $this->flattenHeaders($headers);
            foreach ($this->headersContain as $needle) {
                if (!$this->containsHeaderLine($flatHeaders, $needle)) {
                    return false;
                }
            }
        }

        if ($this->rawEquals !== null) {
            return (string)$raw === $this->rawEquals;
        }

        if ($this->jsonExact !== null || $this->jsonSubset !== null) {
            $decoded = $this->tryDecode($raw);
            if (!is_array($decoded)) {
                return false;
            }
            if ($this->jsonExact !== null && $decoded !== $this->jsonExact) {
                return false;
            }
            if ($this->jsonSubset !== null && !$this->isSubset($this->jsonSubset, $decoded)) {
                return false;
            }
        }

        return true;
    }

    public function mismatchMessage(string $method, string $url, array $headers, ?string $raw): string
    {
        $expected = strtoupper($this->method) . ' ' . $this->url;
        $actual = strtoupper($method) . ' ' . $url;

        return "Buckaroo request mismatch\nexpected: {$expected}\nactual: {$actual}\nbody: " . ($raw ?? '');
    }

    /** @return array{0:int,1:array,2:array|string,3:bool} */
    public function responseSpec(): array
    {
        return [$this->status, $this->responseHeaders, $this->responseBody, $this->isJson];
    }

    private function tryDecode(?string $raw): ?array
    {
        if ($raw === null || $raw === '') {
            return null;
        }

        try {
            return json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return null;
        }
    }

    private function isSubset(array $subset, array $actual): bool
    {
        foreach ($subset as $k => $v) {
            if (!array_key_exists($k, $actual)) {
                return false;
            }
            if (is_array($v)) {
                if (!is_array($actual[$k]) || !$this->isSubset($v, $actual[$k])) {
                    return false;
                }
            } else {
                if ($actual[$k] !== $v) {
                    return false;
                }
            }
        }

        return true;
    }

    private function flattenHeaders(array $headers): array
    {
        $flat = [];
        foreach ($headers as $name => $values) {
            $headerName = strtolower(is_string($name) ? $name : (string)$name);
            foreach ((array)$values as $value) {
                $flat[] = "{$headerName}: " . trim((string)$value);
            }
        }

        return $flat;
    }

    private function containsHeaderLine(array $flatHeaders, string $needle): bool
    {
        $needle = strtolower($needle);
        foreach ($flatHeaders as $line) {
            if (strpos(strtolower($line), $needle) !== false) {
                return true;
            }
        }

        return false;
    }
}
