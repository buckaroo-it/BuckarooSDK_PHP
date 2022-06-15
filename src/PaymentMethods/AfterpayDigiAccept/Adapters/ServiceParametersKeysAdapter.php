<?php

namespace Buckaroo\PaymentMethods\AfterpayDigiAccept\Adapters;

use Buckaroo\Models\Model;

class ServiceParametersKeysAdapter extends \Buckaroo\Models\Adapters\ServiceParametersKeysAdapter
{
    protected string $prefix = '';

    public function __construct(string $prefix, Model $model)
    {
        $this->prefix = $prefix;

        parent::__construct($model);
    }

    public function serviceParameterKeyOf($propertyName): string
    {
        $name = (isset($this->keys[$propertyName]))? $this->keys[$propertyName] : ucfirst($propertyName);

        return $this->prefix . $name;
    }
}