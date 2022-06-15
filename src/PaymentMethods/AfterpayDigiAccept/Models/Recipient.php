<?php

namespace Buckaroo\PaymentMethods\AfterpayDigiAccept\Models;

use Buckaroo\PaymentMethods\AfterpayDigiAccept\Adapters\{AddressServiceParametersKeysAdapter, EmailServiceParametersKeysAdapter, PhoneServiceParametersKeysAdapter, RecipientServiceParametersKeysAdapter};

use Buckaroo\Models\Address;
use Buckaroo\Models\Company;
use Buckaroo\Models\Email;
use Buckaroo\Models\Interfaces\Recipient as RecipientInterface;
use Buckaroo\Models\Phone;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Afterpay\Models\Person;
use Buckaroo\Resources\Constants\RecipientCategory;

class Recipient extends ServiceParameter
{
    private string $type;

    protected RecipientServiceParametersKeysAdapter $recipient;
    protected AddressServiceParametersKeysAdapter $address;
    protected PhoneServiceParametersKeysAdapter $phone;
    protected EmailServiceParametersKeysAdapter $email;

    public function __construct(string $type, ?array $values = null)
    {
        $this->type = $type;

        parent::__construct($values);
    }

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['recipient', 'address', 'phone', 'email']))
            {
                $this->$property($value);

                continue;
            }

            $this->$property = $value;
        }

        return $this;
    }

    public function recipient($recipient = null)
    {
        if(is_array($recipient))
        {
            return $this->recipient($this->getRecipientObject($recipient));
        }

        if($recipient instanceof RecipientServiceParametersKeysAdapter)
        {
            $this->recipient = $recipient;
        }

        return $this->recipient;
    }

    public function address($address = null)
    {
        if(is_array($address))
        {
            return $this->address(new AddressServiceParametersKeysAdapter($this->type, new Address($address)));
        }

        if($address instanceof AddressServiceParametersKeysAdapter)
        {
            $this->address = $address;
        }

        return $this->address;
    }

    public function phone($phone = null)
    {
        if(is_array($phone))
        {
            return $this->phone(new PhoneServiceParametersKeysAdapter($this->type, new Phone($phone)));
        }

        if($phone instanceof PhoneServiceParametersKeysAdapter)
        {
            $this->phone = $phone;
        }

        return $this->phone;
    }

    public function email($email = null)
    {
        if(is_string($email))
        {
            return $this->email(new EmailServiceParametersKeysAdapter($this->type, new Email($email)));
        }

        if($email instanceof EmailServiceParametersKeysAdapter)
        {
            $this->email = $email;
        }

        return $this->email;
    }

    private function getRecipientObject(array $recipient) : RecipientServiceParametersKeysAdapter
    {
        if(($recipient['companyName'] ?? null) || ( $recipient['chamberOfCommerce'] ?? null) || ($recipient['vatNumber'] ?? null))
        {
            return new RecipientServiceParametersKeysAdapter($this->type, new Company($recipient));
        }

        return new RecipientServiceParametersKeysAdapter($this->type, new Person($recipient));
    }
}