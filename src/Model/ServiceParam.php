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

   public function getServiceParamsErrors($country_code)
   {
        $countrySpecificParameters = $this->config->getCountrySpecificParamErrors($country_code);
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

    return $serviceParameters;

   }

   public function getServiceParams(array $params) : array
   {
        $serviceParams = [];
        
        //Format products
        $products = $this->formatProducts($params['products']);
        //Format customer info
        $customerInfo = $this->formatCustomer($params['customer']);
        return array_merge($serviceParams, $products, $customerInfo);
   }

   public function formatProducts(array $products) : array
   {    
        $productId = 1;
        foreach ($products as $product) {
            foreach ($product as $parameter => $value) {
                $params[] = ['name' => (string)$parameter,
                             'value' => (string)$value,
                             'groupType' => 'Article',
                             'groupId' => (string)$productId
                            ];
            }
        $productId++;
        }
        
        return $params;
   }

   public function formatCustomer(array $customer) : array
   {
        $params[] = ['name' => 'Category', 'value' => 'Person', 'groupType' => 'BillingCustomer'];

        foreach ($customer as $type => $info) {
            if ($type == 'billing') {
                foreach ($info as $parameter => $value) {
                    $params[] = ['name' => (string)$parameter,
                                 'value' => (string)$value,
                                 'groupType' => 'BillingCustomer'
                                ];
                }
            }
            if ($type == 'shipping' || $customer['use_billing_info_for_shipping'] == 1) {
                if ($type == 'use_billing_info_for_shipping') {
                    continue;
                }

                foreach ($info as $parameter => $value) {
                    $params[] = ['name' => (string)$parameter,
                                 'value' => (string)$value,
                                 'groupType' => 'ShippingCustomer'
                                ];
                }
            }
        }
        return $params;
   }
}