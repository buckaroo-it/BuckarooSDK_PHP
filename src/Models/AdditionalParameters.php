<?php

namespace Buckaroo\Models;

class AdditionalParameters extends Model
{
    protected array $AdditionalParameter;

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $name => $value)
        {
            $this->AdditionalParameter[] = array(
                'Value'   => $value,
                'Name'    => $name
            );
        }

        return parent::setProperties($data);
    }
}