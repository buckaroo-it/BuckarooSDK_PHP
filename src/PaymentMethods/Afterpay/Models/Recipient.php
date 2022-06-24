<?php

namespace Buckaroo\PaymentMethods\Afterpay\Models;

use Buckaroo\Models\Address;
use Buckaroo\Models\Company;
use Buckaroo\Models\Email;
use Buckaroo\Models\Interfaces\Recipient as RecipientInterface;
use Buckaroo\Models\Phone;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Afterpay\Service\ParameterKeys\{AddressAdapter};
use Buckaroo\PaymentMethods\Afterpay\Service\ParameterKeys\PhoneAdapter;
use Buckaroo\PaymentMethods\Afterpay\Service\ParameterKeys\RecipientAdapter;
use Buckaroo\Resources\Constants\RecipientCategory;

class Recipient extends ServiceParameter
{
    protected string $type;

    protected RecipientInterface $recipient;
    protected AddressAdapter $address;
    protected PhoneAdapter $phone;
    protected Email $email;

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

        if($recipient instanceof RecipientInterface)
        {
            $this->recipient = $recipient;
        }

        return $this->recipient;
    }

    public function address($address = null)
    {
        if(is_array($address))
        {
            return $this->address(new AddressAdapter(new Address($address)));
        }

        if($address instanceof AddressAdapter)
        {
            $this->address = $address;
        }

        return $this->address;
    }

    public function phone($phone = null)
    {
        if(is_array($phone))
        {
            return $this->phone(new PhoneAdapter(new Phone($phone)));
        }

        if($phone instanceof PhoneAdapter)
        {
            $this->phone = $phone;
        }

        return $this->phone;
    }

    public function email($email = null)
    {
        if(is_string($email))
        {
            return $this->email(new Email($email));
        }

        if($email instanceof Email)
        {
            $this->email = $email;
        }

        return $this->email;
    }

    private function getRecipientObject(array $recipient) : RecipientInterface
    {
        switch ($recipient['category']) {
            case RecipientCategory::COMPANY:
                return new RecipientAdapter(new Company($recipient));
            case RecipientCategory::PERSON:
                return new RecipientAdapter(new Person($recipient));
        }

        throw new \Exception('No recipient category found.');
    }

    public function getGroupType(string $key): ?string
    {
        return $this->type . 'Customer';
    }
}