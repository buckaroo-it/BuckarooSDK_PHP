<?php

namespace Buckaroo\Models;

class Payload extends Model
{
    protected ClientIP $clientIP;
    protected string $currency;
    protected string $returnURL;
    protected string $returnURLError;
    protected string $returnURLCancel;
    protected string $returnURLReject;
    protected string $pushURL;
    protected string $pushURLFailure;
    protected string $invoice;
    protected string $description;
    protected string $originalTransactionKey;
    protected AdditionalParameters $additionalParameters;

    public function setProperties(?array $data)
    {
        if(isset($data['additionalParameters']))
        {
            $this->additionalParameters = new AdditionalParameters($data['additionalParameters']);

            unset($data['additionalParameters']);
        }

        if(isset($data['clientIP']))
        {
            $this->clientIP = new ClientIP($data['clientIP']['address'] ?? null, $data['clientIP']['type'] ?? null);

            unset($data['clientIP']);
        }

        return parent::setProperties($data);
    }
}