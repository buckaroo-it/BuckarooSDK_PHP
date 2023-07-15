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

namespace Buckaroo\PaymentMethods\KlarnaPay\Models;

use Buckaroo\Models\Address;
use Buckaroo\Models\Company;
use Buckaroo\Models\Email;
use Buckaroo\Models\Interfaces\Recipient as RecipientInterface;
use Buckaroo\Models\Person;
use Buckaroo\Models\Phone;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\KlarnaPay\Service\ParameterKeys\AddressAdapter;
use Buckaroo\PaymentMethods\KlarnaPay\Service\ParameterKeys\PhoneAdapter;

class Recipient extends ServiceParameter
{
    /**
     * @var string
     */
    private string $type;

    /**
     * @var RecipientInterface
     */
    protected RecipientInterface $recipient;
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
     * @return RecipientInterface|Person
     */
    public function recipient($recipient = null)
    {
        if (is_array($recipient))
        {
            $this->recipient = new Person($recipient);
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
     * @param array $recipient
     * @return RecipientInterface
     * @throws \Exception
     */
    private function getRecipientObject(array $recipient) : RecipientInterface
    {
        switch ($recipient['category'])
        {
            case 'B2B':
                return new Company($recipient);
            case 'B2C':
                return new Person($recipient);
        }

        throw new \Exception('No recipient category found.');
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
