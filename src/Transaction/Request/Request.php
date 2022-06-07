<?php

declare(strict_types=1);

namespace Buckaroo\Transaction\Request;

use ArrayAccess;
use Buckaroo\Resources\Arrayable;
use Exception;
use JsonSerializable;

class Request implements JsonSerializable, ArrayAccess, Arrayable
{
    protected array $data = [];
    protected array $headers = [];
//    protected LoggerInterface $logger;

//    public function __construct(
//        ?LoggerInterface $logger = null
//    ) {
//        $this->logger = $logger ?? DefaultFactory::getDefaultLogger();
//    }

    /** Implement ArrayAccess */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /** Implement ArrayAccess */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    /** Implement ArrayAccess */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    /** Implement ArrayAccess */
    public function offsetGet($offset): mixed
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /** Implement JsonSerializable */
    public function jsonSerialize(): mixed
    {
        return $this->data;
    }

    /**
     * Redirect all method calls prefixed with 'get' or 'set'
     * to check if a param exists with that name
     * Return or set the param if it does
     *
     * @param  string $method
     * @param  array  $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        $prefix = substr($method, 0, 3);
        $param  = substr($method, 3);
        $arg    = isset($args[0]) ? $args[0] : null;

        if ($prefix === 'set') {
            return $this->offsetSet($param, $arg);
        } elseif ($prefix === 'get') {
            return $this->offsetGet($param);
        }

        throw new Exception("Call to undefined method " . __CLASS__ . '::' . $method);
    }

    /** Implement Arrayable */
    public function toArray(): array
    {
        return $this->data;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setHeader($name, $value)
    {
        $this->headers[strtolower($name)] = $value;
    }

    /**
     * @param  string $name
     * @return string
     */
    public function getHeader($name)
    {
        if (isset($this->headers[strtolower($name)])) {
            return $this->headers[strtolower($name)];
        }

        return null;
    }

    /**
     * @return array [ string ]
     */
    public function getHeaders(): array
    {
        return array_map(function ($value, $key) {
            return $key . ': ' . $value;
        }, $this->headers);
    }

    public function getData(): array
    {
        return $this->data;
    }
}
