<?php

declare(strict_types=1);

namespace Tests\Support;

/**
 * Single expected request + canned response
 * Keep it tiny: match by method, url (exact, wildcard, or regex)
 */
final class BuckarooMockRequest
{
    private string $method;
    private string $url; // exact, wildcard like /url/*, or regex like /.../"

    private int $status = 200;
    private array $responseHeaders = [];

    /** @var array|string */
    private $responseBody;

    private bool $isJson = true;

    private ?\Throwable $exception = null;

    private function __construct(string $method, string $url)
    {
        $this->method = strtoupper($method);
        $this->url = $url;
    }

    /** factory */
    public static function json(string $method, string $url, array $payload, int $status = 200, array $headers = []): self
    {
        $i = new self($method, $url);
        $i->isJson = true;
        $i->responseBody = $payload;
        $i->status = $status;
        $i->responseHeaders = $headers;

        return $i;
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

        return (bool) $urlMatches;
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

    public function withException(\Throwable $exception): self
    {
        $this->exception = $exception;

        return $this;
    }

    public function shouldThrow(): bool
    {
        return $this->exception !== null;
    }

    public function getException(): ?\Throwable
    {
        return $this->exception;
    }
}
