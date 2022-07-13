<?php

namespace Buckaroo\Models;

class RefundPayload extends Payload
{
    protected ClientIP $clientIP;
    protected float $amountCredit;

    public function setProperties(?array $data)
    {
        $this->clientIP = new ClientIP($data['clientIP']['address'] ?? null, $data['clientIP']['type'] ?? null);

        unset($data['clientIP']);

        return parent::setProperties($data);
    }

}