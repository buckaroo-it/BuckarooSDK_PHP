<?php

namespace Buckaroo\PaymentMethods\Klarna\Models;

use Buckaroo\Models\Address;
use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\Phone;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Klarna\Service\ParameterKeys\AddressAdapter;
use Buckaroo\PaymentMethods\Klarna\Service\ParameterKeys\EmailAdapter;
use Buckaroo\PaymentMethods\Klarna\Service\ParameterKeys\PhoneAdapter;
use Buckaroo\PaymentMethods\Klarna\Service\ParameterKeys\RecipientAdapter;

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

    public function phone($phone = null)
    {
        if (is_array($phone)) {
            $this->phone = new PhoneAdapter(new Phone($phone), $this->type);
        }

        return $this->phone;
    }

    public function email($email = null)
    {
        if (is_string($email)) {
            $this->email = new EmailAdapter(new Email($email), $this->type);
        }

        return $this->email;
    }

    public function address($address = null)
    {
        if (is_array($address)) {
            $this->address = new AddressAdapter(new Address($address), $this->type);
        }

        return $this->address;
    }

    public function recipient($recipient = null)
    {
        if (is_array($recipient)) {
            $this->recipient = new RecipientAdapter(new Person($recipient), $this->type);
        }

        return $this->recipient;
    }
}
