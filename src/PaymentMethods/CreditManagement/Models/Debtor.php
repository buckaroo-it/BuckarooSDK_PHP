<?php

namespace Buckaroo\PaymentMethods\CreditManagement\Models;

class Debtor extends Invoice
{
    protected bool $addressUnreachable;
    protected bool $emailUnreachable;
    protected bool $mobileUnreachable;
    protected bool $landlineUnreachable;
    protected bool $faxUnreachable;

    protected array $groupData = [
        'address'   => [
            'groupType' => 'Address'
        ],
        'company'   => [
            'groupType' => 'Company'
        ],
        'person'   => [
            'groupType' => 'Person'
        ],
        'debtor'   => [
            'groupType' => 'Debtor'
        ],
        'email'   => [
            'groupType' => 'Email'
        ],
        'phone'   => [
            'groupType' => 'Phone'
        ],
        'addressUnreachable'   => [
            'groupType' => 'Address'
        ],
        'emailUnreachable'   => [
            'groupType' => 'Email'
        ],
        'mobileUnreachable'   => [
            'groupType' => 'Phone'
        ],
        'landlineUnreachable'   => [
            'groupType' => 'Phone'
        ],
        'faxUnreachable'   => [
            'groupType' => 'Phone'
        ],
    ];
}