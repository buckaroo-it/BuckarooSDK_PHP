<?php

namespace Buckaroo\Models;

class AdditionalParameters extends Model
{
    protected array $AdditionalParameter;

    public function setProperties(?array $data)
    {
        $this->AdditionalParameter = $data;

        return parent::setProperties($data);
    }
}