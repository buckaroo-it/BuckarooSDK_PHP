<?php

namespace Buckaroo\PaymentMethods\KlarnaPay\Models;

use Buckaroo\Models\{Address, Company, Email, Person, Phone, ServiceParameter};
use Buckaroo\Models\Interfaces\Recipient as RecipientInterface;
use Buckaroo\PaymentMethods\KlarnaPay\Service\ParameterKeys\{AddressAdapter};
use Buckaroo\PaymentMethods\KlarnaPay\Service\ParameterKeys\PhoneAdapter;

class Recipient extends ServiceParameter
{
    private string $type;

    protected RecipientInterface $recipient;
    protected AddressAdapter $address;
    protected PhoneAdapter $phone;
    protected Email $email;

    public function __construct(string $type, ?array $values = null)
    {
        $this->type = $type;

        parent::__construct($values);
    }

    public function recipient($recipient = null)
    {
        if(is_array($recipient))
        {
            $this->recipient = new Person($recipient);
        }

        return $this->recipient;
    }

    public function address($address = null)
    {
        if(is_array($address))
        {
            $this->address = new AddressAdapter(new Address($address));
        }

        return $this->address;
    }

    public function phone($phone = null)
    {
        if(is_array($phone))
        {
            $this->phone = new PhoneAdapter(new Phone($phone));
        }

        return $this->phone;
    }

    public function email($email = null)
    {
        if(is_string($email))
        {
            $this->email = new Email($email);
        }

        return $this->email;
    }

    private function getRecipientObject(array $recipient) : RecipientInterface
    {
        switch ($recipient['category']) {
            case 'B2B':
                return new Company($recipient);
            case 'B2C':
                return new Person($recipient);
        }

        throw new \Exception('No recipient category found.');
    }

    public function getGroupType(string $key): ?string
    {
        return $this->type . 'Customer';
    }
}