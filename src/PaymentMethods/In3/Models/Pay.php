<?php

namespace Buckaroo\PaymentMethods\In3\Models;

use Buckaroo\Models\Address;
use Buckaroo\Models\Company;
use Buckaroo\Models\Email;
use Buckaroo\Models\Person;
use Buckaroo\Models\Phone;
use Buckaroo\Models\ServiceParameter;
use Buckaroo\PaymentMethods\Billink\Models\Article;
use Buckaroo\PaymentMethods\In3\Service\ParameterKeys\{ArticleAdapter};
use Buckaroo\PaymentMethods\In3\Service\ParameterKeys\AddressAdapter;
use Buckaroo\PaymentMethods\In3\Service\ParameterKeys\CompanyAdapter;
use Buckaroo\PaymentMethods\In3\Service\ParameterKeys\PhoneAdapter;
use Buckaroo\PaymentMethods\Traits\CountableGroupKey;

class Pay extends ServiceParameter
{
    use CountableGroupKey;

    private array $countableProperties = ['articles', 'subtotals'];

    protected string $customerType;
    protected string $invoiceDate;

    protected Person $customer;
    protected CompanyAdapter $company;
    protected AddressAdapter $address;
    protected Email $email;
    protected PhoneAdapter $phone;

    protected array $articles = [];
    protected array $subtotals = [];

    protected array $groupData = [
        'articles'   => [
            'groupType' => 'ProductLine'
        ],
        'address'   => [
            'groupType' => 'Address'
        ],
        'customer'      => [
            'groupType' => 'Person'
        ],
        'company'       => [
            'groupType' => 'Company'
        ],
        'phone'         => [
            'groupType' => 'Phone'
        ],
        'email'         => [
            'groupType' => 'Email'
        ]
    ];

    public function articles(?array $articles = null)
    {
        if(is_array($articles))
        {
            foreach($articles as $article)
            {
                $this->articles[] = new ArticleAdapter(new Article($article));
            }
        }

        return $this->articles;
    }

    public function company($company = null)
    {
        if(is_array($company))
        {
            $this->company = new CompanyAdapter(new Company($company));
        }

        return $this->company;
    }

    public function customer($customer = null)
    {
        if(is_array($customer))
        {
            $this->customer = new Person($customer);
        }

        return $this->customer;
    }

    public function address($address = null)
    {
        if(is_array($address))
        {
            $this->address = new AddressAdapter(new Address($address));
        }

        return $this->address;
    }

    public function email($email = null)
    {
        if(is_string($email))
        {
            $this->email = new Email($email);
        }

        return $this->email;
    }

    public function phone($phone = null)
    {
        if(is_array($phone))
        {
            $this->phone = new PhoneAdapter(new Phone($phone));
        }

        return $this->phone;
    }

    public function subtotals(?array $subtotals = null)
    {
        if(is_array($subtotals))
        {
            foreach($subtotals as $subtotal)
            {
                $this->subtotals[] = new Subtotal($subtotal);
            }
        }

        return $this->subtotals;
    }
}