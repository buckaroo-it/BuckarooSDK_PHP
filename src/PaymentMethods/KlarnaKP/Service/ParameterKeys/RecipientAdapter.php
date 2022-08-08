<?php

namespace Buckaroo\PaymentMethods\KlarnaKP\Service\ParameterKeys;

use Buckaroo\Models\Adapters\ServiceParametersKeysAdapter;
use Buckaroo\Models\Interfaces\Recipient as RecipientInterface;
use Buckaroo\Models\Model;

class RecipientAdapter extends ServiceParametersKeysAdapter implements RecipientInterface
{
    private string $type;

    public function __construct(Model $model, string $type)
    {
        $this->type = $type;

        parent::__construct($model);
    }

    public function serviceParameterKeyOf($propertyName): string
    {
        $propertyName = (isset($this->keys[$propertyName]))? $this->keys[$propertyName] : ucfirst($propertyName);

        return $this->type . $propertyName;
    }
}