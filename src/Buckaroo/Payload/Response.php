<?php

declare(strict_types=1);

namespace Buckaroo\SDK\Buckaroo\Payload;

use ArrayAccess;
use Buckaroo\SDK\Helpers\Arrayable;
use Exception;

class Response implements ArrayAccess, Arrayable
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $curlInfo = [];

    /**
     * @var array
     */
    protected $headers = [];

    public function __construct($data, $curlInfo = [], $headers = [])
    {
        $this->data     = $data;
        $this->curlInfo = $curlInfo;
        $this->headers  = $headers;
    }

    /** Implement ArrayAccess */
    public function offsetSet($offset, $value)
    {
        throw new Exception("Can't set a value of a Response");
    }

    /** Implement ArrayAccess */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /** Implement ArrayAccess */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /** Implement ArrayAccess */
    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * Redirect all method calls prefixed with 'get'
     * to check if a param exists with that name
     * Return the param if it does
     *
     * @param  string $method
     * @param  array  $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        $prefix = substr($method, 0, 3);
        $param  = substr($method, 3);
        // $arg    = isset($args[0]) ? $args[0] : null;

        if ($prefix === 'get') {
            return $this->offsetGet($param);
        }

        throw new Exception("Call to undefined method " . __CLASS__ . '::' . $method);
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->curlInfo['http_code'];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->curlInfo['url'];
    }

    /** Implement Arrayable */
    public function toArray()
    {
        return $this->data;
    }
}
