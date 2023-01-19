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

namespace Buckaroo\PaymentMethods\CreditManagement\Models;

use Buckaroo\Models\Address;
use Buckaroo\Models\Company;
use Buckaroo\Models\Debtor;
use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\Phone;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\CreditManagement\Service\ParameterKeys\ArticleAdapter;
use Buckaroo\PaymentMethods\Traits\CountableGroupKey;

class Invoice extends ServiceParameter
{
    use CountableGroupKey;

    /**
     * @var array|string[]
     */
    private array $countableProperties = ['articles'];

    /**
     * @var string
     */
    protected string $invoiceNumber;
    /**
     * @var float
     */
    protected float $invoiceAmount;
    /**
     * @var float
     */
    protected float $invoiceAmountVAT;
    /**
     * @var string
     */
    protected string $invoiceDate;
    /**
     * @var string
     */
    protected string $dueDate;
    /**
     * @var string
     */
    protected string $schemeKey;
    /**
     * @var int
     */
    protected int $maxStepIndex;
    /**
     * @var string
     */
    protected string $allowedServices;
    /**
     * @var string
     */
    protected string $code;
    /**
     * @var string
     */
    protected string $disallowedServices;
    /**
     * @var string
     */
    protected string $allowedServicesAfterDueDate;
    /**
     * @var string
     */
    protected string $disallowedServicesAfterDueDate;
    /**
     * @var string
     */
    protected string $applyStartRecurrent;
    /**
     * @var string
     */
    protected string $poNumber;
    /**
     * @var array
     */
    protected array $articles = [];

    /**
     * @var Address
     */
    protected Address $address;
    /**
     * @var Company
     */
    protected Company $company;
    /**
     * @var Person
     */
    protected Person $person;
    /**
     * @var Debtor
     */
    protected Debtor $debtor;
    /**
     * @var Email
     */
    protected Email $email;
    /**
     * @var Phone
     */
    protected Phone $phone;

    /**
     * @var array|\string[][]
     */
    protected array $groupData = [
        'articles' => [
            'groupType' => 'ProductLine',
        ],
        'address' => [
            'groupType' => 'Address',
        ],
        'company' => [
            'groupType' => 'Company',
        ],
        'person' => [
            'groupType' => 'Person',
        ],
        'debtor' => [
            'groupType' => 'Debtor',
        ],
        'email' => [
            'groupType' => 'Email',
        ],
        'phone' => [
            'groupType' => 'Phone',
        ],
    ];

    /**
     * @param $address
     * @return Address
     */
    public function address($address = null)
    {
        if (is_array($address))
        {
            $this->address = new Address($address);
        }

        return $this->address;
    }

    /**
     * @param $company
     * @return Company
     */
    public function company($company = null)
    {
        if (is_array($company))
        {
            $this->company = new Company($company);
        }

        return $this->company;
    }

    /**
     * @param $person
     * @return Person
     */
    public function person($person = null)
    {
        if (is_array($person))
        {
            $this->person = new Person($person);
        }

        return $this->person;
    }

    /**
     * @param $debtor
     * @return Debtor
     */
    public function debtor($debtor = null)
    {
        if (is_array($debtor))
        {
            $this->debtor = new Debtor($debtor);
        }

        return $this->debtor;
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
     * @param $phone
     * @return Phone
     */
    public function phone($phone = null)
    {
        if (is_array($phone))
        {
            $this->phone = new Phone($phone);
        }

        return $this->phone;
    }

    /**
     * @param array|null $articles
     * @return array
     */
    public function articles(?array $articles = null)
    {
        if (is_array($articles))
        {
            foreach ($articles as $article)
            {
                $this->articles[] = new ArticleAdapter(new Article($article));
            }
        }

        return $this->articles;
    }
}
