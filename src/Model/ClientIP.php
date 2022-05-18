<?php

namespace Buckaroo\Model;

use Buckaroo\Helpers\Base;
use Buckaroo\Helpers\Constants\IPProtocolVersion;
use Buckaroo\Helpers\Validate;

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
        $this->Address = $ip ?? Base::getRemoteIp();

        if(!Validate::isIp($this->Address))
        {
            throw new \Exception("Invalid IP", $this->Address);
            //$this->throwError(__METHOD__, "Invalid IP", $ip);
        }

        return $this;
    }

    private function setType()
    {
        $this->Type =  IPProtocolVersion::getVersion($this->Address);

        return $this;
    }
}