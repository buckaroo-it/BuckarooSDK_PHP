<?php

namespace Buckaroo\PaymentMethods\AfterpayDigiAccept\Models;

use Buckaroo\Models\Address;
use Buckaroo\Models\Company;
use Buckaroo\Models\Email;
use Buckaroo\Models\Phone;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Afterpay\Models\Person;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys\{PhoneAdapter};
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys\AddressAdapter;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys\EmailAdapter;
use Buckaroo\PaymentMethods\AfterpayDigiAccept\Service\ParameterKeys\RecipientAdapter;

class Recipient extends ServiceParameter
{
    private string $type;

    protected RecipientAdapter $recipient;
    protected AddressAdapter $address;
    protected PhoneAdapter $phone;
    protected EmailAdapter $email;

    public function __construct(string $type, ?array $values = null)
    {
        $this->type = $type;

        parent::__construct($values);
    }

    public function recipient($recipient = null)
    {
        if(is_array($recipient))
        {
            $this->recipient = $this->getRecipientObject($recipient);
        }

        return $this->recipient;
    }

    public function address($address = null)
    {
        if(is_array($address))
        {
            $this->address = new AddressAdapter($this->type, new Address($address));
        }

        return $this->address;
    }

    public function phone($phone = null)
    {
        if(is_array($phone))
        {
            $this->phone = new PhoneAdapter($this->type, new Phone($phone));
        }

        return $this->phone;
    }

    public function email($email = null)
    {
        if(is_string($email))
        {
            $this->email = new EmailAdapter($this->type, new Email($email));
        }

        return $this->email;
    }

    private function getRecipientObject(array $recipient) : RecipientAdapter
    {
        $model = new Person($recipient);

        if(($recipient['companyName'] ?? null) || ( $recipient['chamberOfCommerce'] ?? null) || ($recipient['vatNumber'] ?? null))
        {
            $model = new Company($recipient);
        }

        return new RecipientAdapter($this->type, $model);
    }
}