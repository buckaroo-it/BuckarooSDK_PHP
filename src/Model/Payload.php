<?php

namespace Buckaroo\Model;

class Payload extends Model
{
    public function __construct(?array $payload)
    {
        $this->setProperties($payload);
    }
}