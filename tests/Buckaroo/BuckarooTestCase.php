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

namespace Tests\Buckaroo;

use Buckaroo\BuckarooClient;
use Buckaroo\Config\DefaultConfig;
use Buckaroo\Resources\Constants\Gender;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class BuckarooTestCase extends TestCase
{
    protected BuckarooClient $buckaroo;
    protected static ?string $payTransactionKey = null;
    protected static ?string $authorizeTransactionKey = null;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(getcwd());
        $dotenv->load();

        $this->buckaroo = new BuckarooClient(new DefaultConfig(
            $_ENV['BPE_WEBSITE_KEY'],
            $_ENV['BPE_SECRET_KEY'],
            $_ENV['BPE_MODE'] ?? null,
            $_ENV['BPE_CURRENCY_CODE'] ?? null,
            $_ENV['BPE_RETURN_URL'] ?? null,
            $_ENV['BPE_RETURN_URL_CANCEL'] ?? null,
            $_ENV['BPE_PUSH_URL'] ?? null,
            'TestingPlatform',
            '3.0.0',
            'TestingModule',
            'Testing',
            '2.4.0',
            'nl-NL'
        ));

        parent::__construct();
    }

    protected function getPayPayload(?array $overrides = []): array
    {
        $payload = [
            'billing' => $this->getBillingPayload(),
            'shipping' => $this->getShippingPayload(),
        ];

        return array_merge($this->getBasePayPayload(), $payload, $overrides);
    }

    protected function getRefundPayload(?array $overrides = []): array
    {
        $payload = [
            'amountCredit' => 0.01,
            'invoice' => uniqid(),
        ];
        return array_merge($payload, $overrides);
    }

    protected function getBasePayPayload(?array $exceptKeys = [], ?array $overrides = []): array
    {
        $payload =  [
            'clientIP' => '127.0.0.1',
            'invoice' => uniqid(),
            'currency' => 'CHF',
            'amountDebit' => 100.30,
            'order' => uniqid(),
            'description' => 'Buckaroo SDK Test Transaction',
        ];

        if ($exceptKeys) {
            foreach ($exceptKeys as $key) {
                $this->removeKeyFromArray($payload, $key);
            }
        }

        return array_merge($payload, $overrides);
    }

    protected function getBillingPayload(?array $exceptKeys = [], ?array $overrides = []): array
    {
        $payload =  [
            'recipient' => [
                'category' => 'B2C',
                'title' => 'Female',
                'careOf' => 'John Smith',
                'firstName' => 'John',
                'lastName' => 'Doe',
                'initials' => 'JD',
                'salutation' => 'Male',
                'birthDate' => '1990-01-01',
                'companyName' => 'Buckaroo Test',
                'email' => 'test@buckaroo.nl',
                'phone' => ['mobile' => '0698765433'],
                'conversationLanguage' => 'NL',
                'identificationNumber' => 'IdNumber12345',
                'customerNumber' => 'customerNumber12345',
            ],
            'address' => [
                'street' => 'Hoofdstraat',
                'houseNumber' => '13',
                'houseNumberSuffix' => 'A',
                'zipcode' => '1234AB',
                'city' => 'Heerenveen',
                'country' => 'NL',
            ],
            'phone' => ['mobile' => '0698765433'],
            'email' => 'test@buckaroo.nl',
        ];

        if ($exceptKeys) {
            foreach ($exceptKeys as $key) {
                $this->removeKeyFromArray($payload, $key);
            }
        }

        return array_merge($payload, $overrides);
    }

    protected function getShippingPayload(?array $exceptKeys = [], ?array $overrides = []): array
    {
        $payload =  [
            'recipient' => [
                'category' => 'B2C',
                'careOf' => 'John Smith',
                'title' => 'Male',
                'initials' => 'JD',
                'salutation' => 'Male',
                'companyName' => 'Buckaroo B.V.',
                'firstName' => 'John',
                'lastName' => 'Doe',
                'birthDate' => '1990-01-01',
                'phone' => ['mobile' => '0698765433'],
                'email' => 'test@buckaroo.nl',
                'conversationLanguage' => 'NL',
                'identificationNumber' => 'IdNumber12345',
                'customerNumber' => 'customerNumber12345',
            ],
            'email' => 'test@buckaroo.nl',
            'address' => [
                'street' => 'Kalverstraat',
                'houseNumber' => '13',
                'houseNumberSuffix' => 'A',
                'zipcode' => '4321EB',
                'city' => 'Amsterdam',
                'country' => 'NL',
                'addressType' => 'Standard'
            ],
        ];

        if ($exceptKeys) {
            foreach ($exceptKeys as $key) {
                $this->removeKeyFromArray($payload, $key);
            }
        }

        return array_merge($payload, $overrides);
    }

    private function removeKeyFromArray(array &$array, $keyToRemove): void
    {
        foreach ($array as $key => &$value) {
            if ($key === $keyToRemove) {
                unset($array[$key]);
            } elseif (is_array($value)) {
                $this->removeKeyFromArray($value, $keyToRemove);
            }
        }
    }

    protected function getArticlesPayload(?array $exceptKeys = [], ?array $overrides = []): array
    {
        $payload = [
            [
                'identifier' => 'Articlenumber1',
                'description' => 'Blue Toy Car',
                'vatPercentage' => '21',
                'quantity' => '2',
                'price' => '25.10',
                'imageUrl' => 'https://www.buckaroo.nl/img/logo_menu.png',
                'UnitCode' => 'Pieces'
            ],
            [
                'identifier' => 'Articlenumber2',
                'description' => 'Red Toy Car',
                'vatPercentage' => '21',
                'quantity' => '1',
                'price' => '50.10',
                'imageUrl' => 'https://www.buckaroo.nl/img/logo_menu.png',
                'UnitCode' => 'Pieces'
            ],
        ];

        if ($exceptKeys) {
            foreach ($exceptKeys as $key) {
                $this->removeKeyFromArray($payload, $key);
            }
        }

        return array_merge($payload, $overrides);
    }

    protected function getInvoicePayload(array $append = []): array
    {
        return array_merge($append, [
            'applyStartRecurrent' => 'False',
            'invoiceAmount' => 10.00,
            'invoiceAmountVAT' => 1.00,
            'invoiceDate' => '2024-01-01',
            'dueDate' => '2030-01-01',
            'schemeKey' => 's31w5d',
            'maxStepIndex' => 1,
            'allowedServices' => 'ideal,mastercard',
            'allowedServicesAfterDueDate' => 'ideal,mastercard',
            'debtor' => [
                'code' => 'johnsmith4',
            ],
            'email' => 'youremail@example.nl',
            'phone' => [
                'mobile' => '06198765432',
            ],
            'person' => [
                'culture' => 'nl-NL',
                'title' => 'Msc',
                'initials' => 'JS',
                'firstName' => 'Test',
                'lastNamePrefix' => 'Jones',
                'lastName' => 'Aflever',
                'gender' => Gender::MALE,
            ],
            'company' => [
                'culture' => 'nl-NL',
                'name' => 'My Company Corporation',
                'vatApplicable' => true,
                'vatNumber' => 'NL140619562B01',
                'chamberOfCommerce' => '20091741',
            ],
            'address' => [
                'street' => 'Hoofdtraat',
                'houseNumber' => '90',
                'houseNumberSuffix' => 'A',
                'zipcode' => '8441ER',
                'city' => 'Heerenveen',
                'state' => 'Friesland',
                'country' => 'NL',
            ],
        ]);
    }
}
