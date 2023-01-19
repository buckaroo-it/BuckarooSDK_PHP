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

namespace Buckaroo\PaymentMethods\BuckarooWallet\Models;

use Buckaroo\Models\BankAccount;
use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\BankAccountAdapter;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\CustomerAdapter;
use Buckaroo\PaymentMethods\BuckarooWallet\Service\ParameterKeys\EmailAdapter;

class Wallet extends ServiceParameter
{
    /**
     * @var array|string[]
     */
    protected array $propertiesAsMethods = ['customer', 'email', 'bankAccount'];

    /**
     * @var string
     */
    protected string $walletId;
    /**
     * @var string
     */
    protected string $status;
    /**
     * @var string
     */
    protected string $walletMutationGuid;

    /**
     * @var CustomerAdapter
     */
    protected CustomerAdapter $customer;
    /**
     * @var EmailAdapter
     */
    protected EmailAdapter $email;
    /**
     * @var BankAccountAdapter
     */
    protected BankAccountAdapter $bankAccount;

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
     * @param $bankAccount
     * @return BankAccountAdapter
     */
    public function bankAccount($bankAccount = null)
    {
        if (is_array($bankAccount))
        {
            $this->bankAccount = new BankAccountAdapter(new BankAccount($bankAccount));
        }

        return $this->bankAccount;
    }
}
