<?php

declare(strict_types=1);

namespace Buckaroo\PaymentMethods;

use Buckaroo\Helpers\Base;
use Buckaroo\Payload\TransactionRequest;
use Buckaroo\Payload\TransactionResponse;
use Buckaroo\Model\Customer;
use Buckaroo\Model\Article;

class AfterpayNew extends PaymentMethod
{
    public const CATEGORY_PERSON = 'Person';
    public const CATEGORY_COMPANY = 'Company';

    public const SALUTATION_MR = 'Mr';
    public const SALUTATION_MRS = 'Mrs';
    public const SALUTATION_MISS = 'Miss';

    private $articlesQty = 0;

    public function getCode(): string
    {
        return PaymentMethod::AFTERPAY;
    }

    public function pay(TransactionRequest $request): TransactionResponse
    {
        $request->setServiceVersion(1);
        return parent::pay($request);
    }

    protected function validatePayRequest(TransactionRequest $request): void
    {
        if (!$request->getServiceParameter('Description', 'Article')) {
            $this->throwError(__METHOD__, "Empty articles");
        }

        $this->validatePayRequestCustomer($request, 'BillingCustomer');
        $this->validatePayRequestCustomer($request, 'ShippingCustomer');

        parent::validatePayRequest($request);
    }

    protected function validatePayRequestCustomer(
        TransactionRequest $request,
        string $groupType = 'BillingCustomer'
    ): void {

        $country = $request->getServiceParameter('Country', $groupType);
        if (in_array($country, ["NL", "BE"])) {
            $countrySpecificParameters = ['Salutation' => ['Empty salutation', 'Wrong salutation'],
                                          'BirthDate' => 'Empty birth date',
                                          'Phone' => 'Empty phone',
                                          'MobilePhone' => 'Empty phone',
                                          'StreetNumber' => 'Empty street number'
                                        ];
        } elseif($country == 'FI') {
            $countrySpecificParameters = ['IdentificationNumber' => 'Empty identification number'];
        } else {
            $countrySpecificParameters = [];
        }
        
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

        foreach ($serviceParameters as $serviceParameter => $errorMessage) {
            $result = null;
            $result = $request->getServiceParameter($serviceParameter);

            if ($serviceParameter == 'Category'){

                if (!$result) {
                    $this->throwError(__METHOD__, $errorMessage[0]);
                }
        
                if (!in_array($result, $this->getCategories())) {
                    $this->throwError(__METHOD__, $errorMessage[1]);
                }
            } elseif ($serviceParameter == 'CountrySpecific' && !empty($countrySpecificParameters)) {
                foreach ($countrySpecificParameters as $countrySpecificParameter => $countrySpecificError) {
                    $result = null;
                    $result = $request->getServiceParameter($countrySpecificParameter, $groupType);
        
                    if ($countrySpecificParameter == 'Salutation'){
                        
                        if (!$result) {
                            $this->throwError(__METHOD__, $countrySpecificError[0]);
                        }
                
                        if (!in_array($result, $this->getSalutations())) {
                            $this->throwError(__METHOD__, $countrySpecificError[1]);
                        }
                    } else {
                        if (!$result) {
                            if (in_array($countrySpecificParameter, ['Phone', 'MobilePhone'])) { //at least one phone number required
                                $emptyPhone++;
                                if ($emptyPhone < 2) {
                                    continue;
                                }
                            }                            
                            $this->throwError(__METHOD__, $countrySpecificError);
                        }
                    }
                }
            } elseif(!$result) {
                $this->throwError(__METHOD__, $errorMessage);
            }
        }
    }

    public function setArticleItem(
        TransactionRequest $request,
        Article $article
    ): void {
        if (empty($article->getName())) {
            $this->throwError(__METHOD__, "Empty article's name");
        }

        if (empty($article->getQuantity())) {
            $this->throwError(__METHOD__, "Empty article's quantity");
        }

        if (empty($article->getId())) {
            $this->throwError(__METHOD__, "Empty article's SKU");
        }

        $this->articlesQty++;
        $articlesQty = (string) $article->getQuantity();

        $request->setServiceParameter('Description', $article->getName(), 'Article', $articlesQty);
        $request->setServiceParameter('GrossUnitPrice', (string) $article->getPrice(), 'Article', $articlesQty);
        $request->setServiceParameter('VatPercentage', $article->getVat(), 'Article', $articlesQty);
        $request->setServiceParameter('Quantity', (string) $article->getQuantity(), 'Article', $articlesQty);
        $request->setServiceParameter('Identifier', $article->getId(), 'Article', $articlesQty);
    }

    private function setCustomer(
        TransactionRequest $request,
        Customer $customer,
        string $groupType = 'BillingCustomer'
    ): void {
        $request->setServiceParameter('FirstName', $customer->getFirstname(), $groupType);
        $request->setServiceParameter('LastName', $customer->getLastname(), $groupType);
        $request->setServiceParameter('Street', $customer->getStreet(), $groupType);
        $request->setServiceParameter('StreetNumber', $customer->getHouseNumber(), $groupType);
        if (!empty($customer->getHousenumberAddition())) {
            $request->setServiceParameter('StreetNumberAdditional', $customer->getHousenumberAddition(), $groupType);
        }
        $request->setServiceParameter('PostalCode', $customer->getPostalCode(), $groupType);
        $request->setServiceParameter('City', $customer->getCity(), $groupType);
        $request->setServiceParameter('Country', $customer->getCountrycode(), $groupType);
        $request->setServiceParameter('Email', $customer->getEmail(), $groupType);

        if (in_array($customer->getCountrycode(), ["NL", "BE"])) {
            if ($customer->getPhoneNumber()) {
                if ($customer->getCountryCode() == "NL") {
                    $typeDutchPhone = $this->getTypeDutchPhoneNumber($customer->getPhoneNumber());
                    $request->setServiceParameter(
                        $typeDutchPhone,
                        Base::stringFormatPhone($customer->getPhoneNumber()),
                        $groupType
                    );
                } elseif ($customer->getCountryCode() == "BE") {
                    $typeBelgiumPhone = $this->getTypeBelgiumPhoneNumber($customer->getPhoneNumber());
                    $request->setServiceParameter(
                        $typeBelgiumPhone,
                        Base::stringFormatPhone($customer->getPhoneNumber()),
                        $groupType
                    );
                }
            }
        } elseif ($customer->getCountryCode() == "FI") {
            $request->setServiceParameter('IdentificationNumber', $customer->getIdentificationId(), $groupType);
        }

        if (!empty($customer->getCustomerId())) {
            $request->setServiceParameter('CustomerNumber', $customer->getCustomerId(), $groupType);
        }

        $request->setServiceParameter('Category', $customer->getCategory(), $groupType);
        $request->setServiceParameter('Salutation', $customer->getSalutation(), $groupType);
        $request->setServiceParameter('BirthDate', $customer->getBirthday(), $groupType);
    }

    public function setBillingCustomer(TransactionRequest $request, Customer $customer): void
    {
        $this->setCustomer($request, $customer, 'BillingCustomer');
    }

    public function setShippingCustomer(TransactionRequest $request, Customer $customer): void
    {
        $this->setCustomer($request, $customer, 'ShippingCustomer');
    }

    protected function getTypeDutchPhoneNumber(string $phone_number): string
    {
        $type = 'Phone';
        if (preg_match("/^(\+|00|0)(31\s?)?(6){1}[\s0-9]{8}/", $phone_number)) {
            $type = 'MobilePhone';
        }
        return $type;
    }

    protected function getTypeBelgiumPhoneNumber(string $phone_number): string
    {
        $type = 'Phone';
        if (preg_match("/^(\+|00|0)(32\s?)?(4){1}[\s0-9]{8}/", $phone_number)) {
            $type = 'MobilePhone';
        }

        return $type;
    }

    public function getCategories(): array
    {
        return [self::CATEGORY_PERSON, self::CATEGORY_COMPANY];
    }

    public function getSalutations(): array
    {
        return [self::SALUTATION_MR, self::SALUTATION_MRS, self::SALUTATION_MISS];
    }
}
