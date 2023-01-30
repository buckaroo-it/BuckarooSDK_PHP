<?php
/*
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * It is available through the world-wide-web at this URL:
 * https://tldrlegal.com/license/mit-license
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to support@buckaroo.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact support@buckaroo.nl for more information.
 *
 * @copyright Copyright (c) Buckaroo B.V.
 * @license   https://tldrlegal.com/license/mit-license
 */

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

    /**
     * @var array|string[]
     */
    private array $countableProperties = ['attachments'];

    /**
     * @var CustomerAdapter
     */
    protected CustomerAdapter $customer;
    /**
     * @var EmailAdapter
     */
    protected EmailAdapter $email;

    /**
     * @var bool
     */
    protected bool $merchantSendsEmail;
    /**
     * @var string
     */
    protected string $expirationDate;
    /**
     * @var string
     */
    protected string $paymentMethodsAllowed;
    /**
     * @var string
     */
    protected string $attachment;

    /**
     * @var array
     */
    protected array $attachments = [];

    /**
     * @param $customer
     * @return CustomerAdapter
     */
    public function customer($customer = null)
    {
        if (is_array($customer))
        {
            $this->customer = new CustomerAdapter(new Person($customer));
        }

        return $this->customer;
    }

    /**
     * @param $email
     * @return EmailAdapter
     */
    public function email($email = null)
    {
        if (is_string($email))
        {
            $this->email = new EmailAdapter(new Email($email));
        }

        return $this->email;
    }

    /**
     * @param array|null $attachments
     * @return array
     */
    public function attachments(?array $attachments = null)
    {
        if (is_array($attachments))
        {
            foreach ($attachments as $attachment)
            {
                $this->attachments[] = new AttachmentAdapter(new Attachment($attachment));
            }
        }

        return $this->attachments;
    }
}
