<?php

namespace Buckaroo\Services;

use Buckaroo\Helpers\Arrayable;
use Buckaroo\Model\Address;
use Buckaroo\Model\Article;
use Buckaroo\Model\Customer;

class AfterpayParametersService implements Arrayable
{
    private $parameters = [];

    public function processArticles(array $articles)
    {
        foreach($articles as $groupKey => $article)
        {
            $groupKey += 1;

            $article = (new Article())->setProperties($article);

            $this->attachArticle($groupKey, $article);
        }
    }

    public function attachArticle(int $groupKey, Article $article)
    {
        $this->parameters[] = [
            "Name"              => "Identifier",
            "Value"             => $article->identifier,
            "GroupType"         => "Article",
            "GroupID"           => $groupKey
        ];

        $this->parameters[] = [
            "Name"              => "Description",
            "Value"             => $article->description,
            "GroupType"         => "Article",
            "GroupID"           => $groupKey
        ];

        $this->parameters[] = [
            "Name"              => "VatPercentage",
            "Value"             => $article->vatPercentage,
            "GroupType"         => "Article",
            "GroupID"           => $groupKey
        ];

        $this->parameters[] = [
            "Name"              => "Quantity",
            "Value"             => $article->quantity,
            "GroupType"         => "Article",
            "GroupID"           => $groupKey
        ];

        $this->parameters[] = [
            "Name"              => "GrossUnitPrice",
            "Value"             => $article->grossUnitPrice,
            "GroupType"         => "Article",
            "GroupID"           => $groupKey
        ];
    }

    public function processCustomer(array $customer)
    {
        if($customer)
        {
            $customer = (new Customer())->setProperties($customer);

            $this->attachCustomerAddress('BillingCustomer', $customer->billing);
            $this->attachCustomerAddress('ShippingCustomer', $customer->shipping);
        }
    }

    public function attachCustomerAddress(string $groupType, Address $address)
    {
        $this->parameters[] = [
            "Name"              => "Category",
            "Value"             => "Person",
            "GroupType"         => $groupType
        ];

        $this->parameters[] = [
            "Name"              => "FirstName",
            "Value"             => $address->firstName,
            "GroupType"         => $groupType
        ];

        $this->parameters[] = [
            "Name"              => "LastName",
            "Value"             => $address->lastName,
            "GroupType"         => $groupType
        ];

        $this->parameters[] = [
            "Name"              => "Email",
            "Value"             => $address->email,
            "GroupType"         => $groupType
        ];

        $this->parameters[] = [
            "Name"              => "Phone",
            "Value"             => $address->phone,
            "GroupType"         => $groupType
        ];

        $this->parameters[] = [
            "Name"              => "Street",
            "Value"             => $address->street,
            "GroupType"         => $groupType
        ];

        $this->parameters[] = [
            "Name"              => "StreetNumber",
            "Value"             => $address->streetNumber,
            "GroupType"         => $groupType
        ];

        $this->parameters[] = [
            "Name"              => "StreetNumberAdditional",
            "Value"             => $address->streetNumber,
            "GroupType"         => $groupType
        ];

        $this->parameters[] = [
            "Name"              => "PostalCode",
            "Value"             => $address->postalCode,
            "GroupType"         => $groupType
        ];

        $this->parameters[] = [
            "Name"              => "City",
            "Value"             => $address->city,
            "GroupType"         => $groupType
        ];

        $this->parameters[] = [
            "Name"              => "Country",
            "Value"             => $address->country,
            "GroupType"         => $groupType
        ];

        $this->parameters[] = [
            "Name"              => "Salutation",
            "Value"             => $address->salutation,
            "GroupType"         => $groupType
        ];

        $this->parameters[] = [
            "Name"              => "BirthDate",
            "Value"             => $address->birthDate,
            "GroupType"         => $groupType
        ];
    }

    public function toArray(): array
    {
        return $this->parameters;
    }
}