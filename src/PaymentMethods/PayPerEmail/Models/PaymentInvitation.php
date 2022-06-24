<?php

namespace Buckaroo\PaymentMethods\PayPerEmail\Models;

use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\PayPerEmail\Service\ParameterKeys\AttachmentAdapter;
use Buckaroo\PaymentMethods\PayPerEmail\Service\ParameterKeys\CustomerAdapter;
use Buckaroo\PaymentMethods\PayPerEmail\Service\ParameterKeys\EmailAdapter;
use Buckaroo\PaymentMethods\Traits\CountableGroupKey;

class PaymentInvitation extends ServiceParameter
{
    use CountableGroupKey;

    private array $countableProperties = ['attachments'];

    protected CustomerAdapter $customer;
    protected EmailAdapter $email;

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
            return $this->customer(new CustomerAdapter(new Person($customer)));
        }

        if($customer instanceof CustomerAdapter)
        {
            $this->customer = $customer;
        }

        return $this->customer;
    }

    public function email($email = null)
    {
        if(is_string($email))
        {
            return $this->email(new EmailAdapter(new Email($email)));
        }

        if($email instanceof EmailAdapter)
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
                $this->attachments[] = new AttachmentAdapter(new Attachment($attachment));
            }
        }

        return $this->attachments;
    }
}