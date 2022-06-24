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

    public function customer($customer = null)
    {
        if(is_array($customer))
        {
            $this->customer = new CustomerAdapter(new Person($customer));
        }

        return $this->customer;
    }

    public function email($email = null)
    {
        if(is_string($email))
        {
            $this->email = new EmailAdapter(new Email($email));
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