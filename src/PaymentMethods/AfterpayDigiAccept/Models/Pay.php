<?php

namespace Buckaroo\PaymentMethods\AfterpayDigiAccept\Models;

use Buckaroo\Models\Article;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Adapters\ArticleServiceParametersKeysAdapter;

class Pay extends ServiceParameter
{
    protected array $groupData = [
        'articles'   => [
            'groupType' => 'Article'
        ]
    ];

    protected string $billingTitle;
    protected int $billingGender;
    protected string $billingInitials;
    protected string $billingLastNamePrefix;
    protected string $billingLastName;
    protected string $billingBirthDate;
    protected string $billingStreet;
    protected string $billingHouseNumber;
    protected string $billingHouseNumberSuffix;
    protected string $billingPostalCode;
    protected string $billingCity;
    protected string $billingCountry;
    protected string $billingEmail;
    protected string $billingPhoneNumber;
    protected string $billingLanguage;
    protected bool $addressesDiffer;
    protected string $shippingTitle;
    protected string $shippingGender;
    protected string $shippingInitials;
    protected string $shippingLastNamePrefix;
    protected string $shippingLastName;
    protected string $shippingBirthDate;
    protected string $shippingStreet;
    protected string $shippingHouseNumber;
    protected string $shippingHouseNumberSuffix;
    protected string $shippingPostalCode;
    protected string $shippingCity;
    protected string $shippingCountryCode;
    protected string $shippingEmail;
    protected string $shippingPhoneNumber;
    protected string $shippingLanguage;
    protected float $shippingCosts;
    protected string $customerAccountNumber;
    protected string $customerIPAddress;
    protected bool $b2b;
    protected string $companyCOCRegistration;
    protected string $companyName;
    protected string $costCentre;
    protected string $department;
    protected string $establishmentNumber;
    protected string $vatNumber;
    protected bool $accept = true;
    protected array $articles = [];

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if($property == 'articles')
            {
                foreach($value as $article)
                {
                    $this->articles[] = new ArticleServiceParametersKeysAdapter(new Article($article));
                }

                continue;
            }

            $this->$property = $value;
        }

        return $this;
    }

    public function getGroupKey(string $key, ?int $keyCount = 0): ?int
    {
        if($key == 'articles' && is_numeric($keyCount))
        {
            return intval($keyCount) + 1;
        }

        return $this->groupData[$key]['groupKey'] ?? null;
    }

//    public function getGroupKey(string $key, ?int $keyCount = 0): ?int
//    {
//        dd("sjdiofjsdf");
//        if($key == 'articles')
//        {
//            dd("here");
//        }
//
//        return $this->groupData[$key]['groupKey'] ?? null;
//    }
}