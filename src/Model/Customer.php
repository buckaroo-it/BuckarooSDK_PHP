<?php

declare(strict_types=1);

namespace Buckaroo\Model;

class Customer
{
    protected string $countryCode = '';
    protected string $firstName = '';
    protected string $lastName = '';
    protected string $street = '';
    protected string $houseNumber = '';
    protected string $houseNumberAddition = '';
    protected string $postalCode = '';
    protected string $city = '';
    protected string $email = '';
    protected string $phoneNumber = '';
    protected string $customerId = '';
    protected string $identificationId = '';
    protected string $category = '';
    protected string $salutation = '';
    protected string $birthday = '';

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street)
    {
        $this->street = $street;

        return $this;
    }

    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(?string $houseNumber)
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    public function getHouseNumberAddition(): ?string
    {
        return $this->houseNumberAddition;
    }

    public function setHouseNumberAddition(?string $houseNumberAddition)
    {
        $this->houseNumberAddition = $houseNumberAddition;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city)
    {
        $this->city = $city;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function setCustomerId(?string $customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getIdentificationId(): ?string
    {
        return $this->identificationId;
    }

    public function setIdentificationId(?string $identificationId)
    {
        $this->identificationId = $identificationId;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category)
    {
        $this->category = $category;

        return $this;
    }

    public function getSalutation(): ?string
    {
        return $this->salutation;
    }

    public function setSalutation(?string $salutation)
    {
        $this->salutation = $salutation;

        return $this;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(?string $birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }
}
