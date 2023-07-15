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

namespace Buckaroo\PaymentMethods\Tinka\Models;

use Buckaroo\Models\Address;
use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\Phone;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Tinka\Service\ParameterKeys\AddressAdapter;
use Buckaroo\PaymentMethods\Tinka\Service\ParameterKeys\CustomerAdapter;
use Buckaroo\PaymentMethods\Tinka\Service\ParameterKeys\PhoneAdapter;

class Recipient extends ServiceParameter
{
    /**
     * @var string
     */
    private string $type;

    /**
     * @var CustomerAdapter
     */
    protected CustomerAdapter $recipient;

    /**
     * @var AddressAdapter
     */
    protected AddressAdapter $address;
    /**
     * @var PhoneAdapter
     */
    protected PhoneAdapter $phone;
    /**
     * @var Email
     */
    protected Email $email;

    /**
     * @param string $type
     * @param array|null $values
     */
    public function __construct(string $type, ?array $values = null)
    {
        $this->type = $type;

        parent::__construct($values);
    }

    /**
     * @param $recipient
     * @return CustomerAdapter
     */
    public function recipient($recipient = null)
    {
        if (is_array($recipient))
        {
            $this->recipient = new CustomerAdapter(new Person($recipient));
        }

        return $this->recipient;
    }

    /**
     * @param $address
     * @return AddressAdapter
     */
    public function address($address = null)
    {
        if (is_array($address))
        {
            $this->address = new AddressAdapter(new Address($address));
        }

        return $this->address;
    }

    /**
     * @param $phone
     * @return PhoneAdapter
     */
    public function phone($phone = null)
    {
        if (is_array($phone))
        {
            $this->phone = new PhoneAdapter(new Phone($phone));
        }

        return $this->phone;
    }

    /**
     * @param $email
     * @return Email
     */
    public function email($email = null)
    {
        if (is_string($email))
        {
            $this->email = new Email($email);
        }

        return $this->email;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getGroupType(string $key): ?string
    {
        return $this->type . 'Customer';
    }
}
