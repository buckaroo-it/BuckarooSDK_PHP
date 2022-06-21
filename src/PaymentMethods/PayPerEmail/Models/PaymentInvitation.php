<?php

namespace Buckaroo\PaymentMethods\PayPerEmail\Models;

use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\PayPerEmail\Adapters\AttachmentServiceParametersKeysAdapter;
use Buckaroo\PaymentMethods\PayPerEmail\Adapters\CustomerServiceParametersKeysAdapter;
use Buckaroo\PaymentMethods\PayPerEmail\Adapters\EmailServiceParametersKeysAdapter;

class PaymentInvitation extends ServiceParameter
{
    protected CustomerServiceParametersKeysAdapter $customer;
    protected EmailServiceParametersKeysAdapter $email;

    protected bool $merchantSendsEmail;
    protected string $expirationDate;
    protected string $paymentMethodsAllowed;
    protected string $attachment;

    protected array $attachments = [];

    public function setProperties(?array $data)
    {
        foreach($data ?? array() as $property => $value)
        {
            if(in_array($property, ['customer', 'email', 'attachments']))
            {
                $this->$property($value);

                continue;
            }

            $this->$property = $value;
        }

        return $this;
    }

    public function customer($customer = null)
    {
        if(is_array($customer))
        {
            return $this->customer(new CustomerServiceParametersKeysAdapter(new Person($customer)));
        }

        if($customer instanceof CustomerServiceParametersKeysAdapter)
        {
            $this->customer = $customer;
        }

        return $this->customer;
    }

    public function email($email = null)
    {
        if(is_string($email))
        {
            return $this->email(new EmailServiceParametersKeysAdapter(new Email($email)));
        }

        if($email instanceof EmailServiceParametersKeysAdapter)
        {
            $this->email = $email;
        }

        return $this->email;
    }

    public function attachments(?array $attachments = null)
    {
        if(is_array($attachments))
        {
            foreach($attachments as $attachment)
            {
                $this->attachments[] = new AttachmentServiceParametersKeysAdapter(new Attachment($attachment));
            }
        }

        return $this->attachments;
    }

    public function getGroupKey(string $key, ?int $keyCount = 0): ?int
    {
        if($key == 'attachments' && is_numeric($keyCount))
        {
            return intval($keyCount) + 1;
        }

        return $this->groupData[$key]['groupKey'] ?? null;
    }
}