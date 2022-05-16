<?php
declare(strict_types=1);

namespace Buckaroo\Model;

class Config extends Model
{
    public function getCountrySpecificParamErrors($country_code)
    {
        switch($country_code)
        {
            case 'FI':
                $countrySpecificParameters = [
                    'IdentificationNumber' => 'Empty identification number'
                ];
            break;
            
            case 'NL':
            case 'BE':
                $countrySpecificParameters = [
                    'Salutation' => ['Empty salutation', 'Wrong salutation'],
                    'BirthDate' => 'Empty birth date',
                    'Phone' => 'Empty phone',
                    'MobilePhone' => 'Empty phone',
                    'StreetNumber' => 'Empty street number'
                ];
            break;
            default:
                $countrySpecificParameters = [];
            break;
        }

        return $countrySpecificParameters;
    }
}