<?php

namespace Buckaroo\Models;

use Buckaroo\Resources\Constants\IPProtocolVersion;

class ClientIP extends Model
{
    protected $Type, $Address;

    public function __construct(?string $ip = null)
    {
        $this->setAddress($ip);
        $this->setType();
    }

    private function setAddress(?string $ip)
    {
        $this->Address = $ip ?? $this->getRemoteIp();

        return $this;
    }

    private function setType()
    {
        $this->Type =  IPProtocolVersion::getVersion($this->Address);

        return $this;
    }

    private function getRemoteIp()
    {
        $headers = function_exists('apache_request_headers') ? apache_request_headers() : $_SERVER;

        /**
         * Get the forwarded IP if it exists
         */
        if (isset($headers['X-Forwarded-For']) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP)) {
            return $headers['X-Forwarded-For'];
        }

        if (isset($headers['HTTP_X_FORWARDED_FOR']) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
            return $headers['HTTP_X_FORWARDED_FOR'];
        }

        if (isset($_SERVER['REMOTE_ADDR']) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return '127.0.0.1';
    }
}