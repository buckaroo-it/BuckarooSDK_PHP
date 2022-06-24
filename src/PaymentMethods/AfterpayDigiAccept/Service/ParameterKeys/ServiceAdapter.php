<?php

namespace Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys;

use Buckaroo\Models\Model;

class ServiceAdapter extends \Buckaroo\Models\Adapters\ServiceParametersKeysAdapter
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