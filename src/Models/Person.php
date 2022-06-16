<?php

declare(strict_types=1);

namespace Buckaroo\Models;

use Buckaroo\Models\Interfaces\Recipient;

class Person extends Model implements Recipient
{
    protected string $category;
    protected string $gender;
    protected string $culture;
    protected string $careOf;
    protected string $title;
    protected string $initials;
    protected string $name;
    protected string $firstName;
    protected string $lastNamePrefix;
    protected string $lastName;
    protected string $birthDate;
    protected string $placeOfBirth;
}
