<?php
declare(strict_types=1);

namespace Buckaroo\Model;
use Buckaroo\Model\Config;

class ServiceParam
{
   public function __construct(Config $config)
   {
       $this->config = $config;
   }

   public function getServiceParams($country_code)
   {
        $countrySpecificParameters = $this->config->getCountrySpecificParam($country_code);
        $serviceParameters = ['Category' => ['Empty category', 'Wrong category'],
        'FirstName' => 'Empty billing info',
        'LastName' => 'Empty last name',
        'Street' => 'Empty street',
        'PostalCode' => 'Empty postal code',
        'City' => 'Empty city',
        'Country' => 'Empty country',
        'Email' => 'Empty email',
        'CountrySpecific' => $countrySpecificParameters
    ];

   }
}