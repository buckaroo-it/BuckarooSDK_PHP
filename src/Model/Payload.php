<?php

namespace Buckaroo\Model;

class Payload extends Model
{
    public function __construct(?array $payload)
    {
        $this->setPayload($payload);
    }

    protected function setPayload(?array $payload)
    {
        if($payload)
        {
            foreach($payload ?? array() as $property => $value)
            {
                $this->$property = $value;
            }
        }
    }
}