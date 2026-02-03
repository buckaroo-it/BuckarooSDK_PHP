<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods;

use Buckaroo\Exceptions\BuckarooException;
use Buckaroo\PaymentMethods\Afterpay\Afterpay;
use Buckaroo\PaymentMethods\ApplePay\ApplePay;
use Buckaroo\PaymentMethods\Bancontact\Bancontact;
use Buckaroo\PaymentMethods\CreditCard\CreditCard;
use Buckaroo\PaymentMethods\GiftCard\GiftCard;
use Buckaroo\PaymentMethods\GooglePay\GooglePay;
use Buckaroo\PaymentMethods\iDeal\iDeal;
use Buckaroo\PaymentMethods\In3\In3;
use Buckaroo\PaymentMethods\KlarnaKP\KlarnaKP;
use Buckaroo\PaymentMethods\NoServiceSpecifiedPayment\NoServiceSpecifiedPayment;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\PaymentMethodFactory;
use Buckaroo\PaymentMethods\Paypal\Paypal;
use Buckaroo\PaymentMethods\Przelewy24\Przelewy24;
use Buckaroo\PaymentMethods\SEPA\SEPA;
use Buckaroo\PaymentMethods\Thunes\Thunes;
use Buckaroo\PaymentMethods\Trustly\Trustly;
use Buckaroo\Transaction\Client;
use Tests\TestCase;

class PaymentMethodFactoryTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->useMock();

        $this->client = $this->buckaroo->client();
    }

    public function test_it_creates_payment_method_by_name(): void
    {
        $factory = new PaymentMethodFactory($this->client, 'ideal');

        $paymentMethod = $factory->getPaymentMethod();

        $this->assertInstanceOf(PaymentMethod::class, $paymentMethod);
        $this->assertInstanceOf(iDeal::class, $paymentMethod);
    }

    public function test_it_creates_payment_method_via_static_get(): void
    {
        $paymentMethod = PaymentMethodFactory::get($this->client, 'ideal');

        $this->assertInstanceOf(PaymentMethod::class, $paymentMethod);
        $this->assertInstanceOf(iDeal::class, $paymentMethod);
    }

    /**
     * @dataProvider paymentMethodProvider
     */
    public function test_it_creates_supported_payment_methods(string $alias, string $expectedClass): void
    {
        $paymentMethod = PaymentMethodFactory::get($this->client, $alias);

        $this->assertInstanceOf(PaymentMethod::class, $paymentMethod);
        $this->assertInstanceOf($expectedClass, $paymentMethod);
    }

    public static function paymentMethodProvider(): array
    {
        return [
            'ideal' => ['ideal', iDeal::class],
            'creditcard' => ['creditcard', CreditCard::class],
            'paypal' => ['paypal', Paypal::class],
            'applepay' => ['applepay', ApplePay::class],
            'googlepay' => ['googlepay', GooglePay::class],
            'bancontact' => ['bancontact', Bancontact::class],
            'afterpay' => ['afterpay', Afterpay::class],
            'klarnakp' => ['klarnakp', KlarnaKP::class],
            'in3' => ['in3', In3::class],
            'sepadirectdebit' => ['sepadirectdebit', SEPA::class],
            'giftcard' => ['giftcard', GiftCard::class],
            'thunes' => ['thunes', Thunes::class],
            'trustly' => ['trustly', Trustly::class],
            'przelewy24' => ['przelewy24', Przelewy24::class],
        ];
    }

    /**
     * @dataProvider creditCardAliasProvider
     */
    public function test_it_resolves_credit_card_aliases_to_credit_card_class(string $alias): void
    {
        $paymentMethod = PaymentMethodFactory::get($this->client, $alias);

        $this->assertInstanceOf(CreditCard::class, $paymentMethod);
    }

    public static function creditCardAliasProvider(): array
    {
        return [
            'creditcard' => ['creditcard'],
            'mastercard' => ['mastercard'],
            'visa' => ['visa'],
            'amex' => ['amex'],
            'vpay' => ['vpay'],
            'maestro' => ['maestro'],
            'visaelectron' => ['visaelectron'],
            'cartebleuevisa' => ['cartebleuevisa'],
            'cartebancaire' => ['cartebancaire'],
            'dankort' => ['dankort'],
            'nexi' => ['nexi'],
            'postepay' => ['postepay'],
        ];
    }

    /**
     * @dataProvider giftCardAliasProvider
     */
    public function test_it_resolves_gift_card_aliases_to_gift_card_class(string $alias): void
    {
        $paymentMethod = PaymentMethodFactory::get($this->client, $alias);

        $this->assertInstanceOf(GiftCard::class, $paymentMethod);
    }

    public static function giftCardAliasProvider(): array
    {
        return [
            'giftcard' => ['giftcard'],
            'westlandbon' => ['westlandbon'],
            'babygiftcard' => ['babygiftcard'],
            'vvvgiftcard' => ['vvvgiftcard'],
            'fashioncheque' => ['fashioncheque'],
            'boekenbon' => ['boekenbon'],
        ];
    }

    /**
     * @dataProvider thunesAliasProvider
     */
    public function test_it_resolves_thunes_aliases_to_thunes_class(string $alias): void
    {
        $paymentMethod = PaymentMethodFactory::get($this->client, $alias);

        $this->assertInstanceOf(Thunes::class, $paymentMethod);
    }

    public static function thunesAliasProvider(): array
    {
        return [
            'thunes' => ['thunes'],
            'monizzemealvoucher' => ['monizzemealvoucher'],
            'monizzeecovoucher' => ['monizzeecovoucher'],
            'sodexomealvoucher' => ['sodexomealvoucher'],
        ];
    }

    /**
     * @dataProvider sepaAliasProvider
     */
    public function test_it_resolves_sepa_aliases_to_sepa_class(string $alias): void
    {
        $paymentMethod = PaymentMethodFactory::get($this->client, $alias);

        $this->assertInstanceOf(SEPA::class, $paymentMethod);
    }

    public static function sepaAliasProvider(): array
    {
        return [
            'sepadirectdebit' => ['sepadirectdebit'],
            'sepa' => ['sepa'],
        ];
    }

    /**
     * @dataProvider bancontactAliasProvider
     */
    public function test_it_resolves_bancontact_aliases_to_bancontact_class(string $alias): void
    {
        $paymentMethod = PaymentMethodFactory::get($this->client, $alias);

        $this->assertInstanceOf(Bancontact::class, $paymentMethod);
    }

    public static function bancontactAliasProvider(): array
    {
        return [
            'bancontact' => ['bancontact'],
            'bancontactmrcash' => ['bancontactmrcash'],
        ];
    }

    public function test_it_handles_case_insensitive_names(): void
    {
        $uppercase = PaymentMethodFactory::get($this->client, 'IDEAL');
        $lowercase = PaymentMethodFactory::get($this->client, 'ideal');
        $mixedCase = PaymentMethodFactory::get($this->client, 'IdEaL');

        $this->assertInstanceOf(iDeal::class, $uppercase);
        $this->assertInstanceOf(iDeal::class, $lowercase);
        $this->assertInstanceOf(iDeal::class, $mixedCase);
    }

    public function test_it_handles_case_insensitive_credit_card_aliases(): void
    {
        $uppercase = PaymentMethodFactory::get($this->client, 'VISA');
        $lowercase = PaymentMethodFactory::get($this->client, 'visa');
        $mixedCase = PaymentMethodFactory::get($this->client, 'ViSa');

        $this->assertInstanceOf(CreditCard::class, $uppercase);
        $this->assertInstanceOf(CreditCard::class, $lowercase);
        $this->assertInstanceOf(CreditCard::class, $mixedCase);
    }

    public function test_it_throws_exception_for_invalid_payment_method(): void
    {
        $this->expectException(BuckarooException::class);
        $this->expectExceptionMessage('Wrong payment method code has been given');

        PaymentMethodFactory::get($this->client, 'nonexistent_payment_method');
    }

    public function test_it_returns_no_service_for_null_payment_method(): void
    {
        $paymentMethod = PaymentMethodFactory::get($this->client, null);

        $this->assertInstanceOf(NoServiceSpecifiedPayment::class, $paymentMethod);
    }

    public function test_it_returns_no_service_for_empty_string_payment_method(): void
    {
        // Empty string is treated as falsy, so NoServiceSpecifiedPayment is returned
        $paymentMethod = PaymentMethodFactory::get($this->client, '');

        $this->assertInstanceOf(NoServiceSpecifiedPayment::class, $paymentMethod);
    }

    public function test_it_stores_service_code_from_factory(): void
    {
        $paymentMethod = PaymentMethodFactory::get($this->client, 'ideal');

        // For iDeal, paymentName returns the hardcoded 'ideal'
        $this->assertSame('ideal', $paymentMethod->paymentName());
    }

    public function test_payment_method_has_correct_payment_name(): void
    {
        $ideal = PaymentMethodFactory::get($this->client, 'ideal');
        $paypal = PaymentMethodFactory::get($this->client, 'paypal');
        $bancontact = PaymentMethodFactory::get($this->client, 'bancontact');

        $this->assertSame('ideal', $ideal->paymentName());
        $this->assertSame('paypal', $paypal->paymentName());
        // Bancontact has hardcoded paymentName 'bancontactmrcash'
        $this->assertSame('bancontactmrcash', $bancontact->paymentName());
    }

    public function test_sepa_has_correct_payment_name(): void
    {
        $sepa = PaymentMethodFactory::get($this->client, 'sepa');

        // SEPA has hardcoded paymentName 'SepaDirectDebit'
        $this->assertSame('SepaDirectDebit', $sepa->paymentName());
    }

    public function test_thunes_has_correct_payment_name(): void
    {
        $thunes = PaymentMethodFactory::get($this->client, 'monizzemealvoucher');

        // Thunes has hardcoded paymentName 'thunes'
        $this->assertSame('thunes', $thunes->paymentName());
    }

    public function test_giftcard_has_correct_payment_name(): void
    {
        $giftcard = PaymentMethodFactory::get($this->client, 'westlandbon');

        // GiftCard has hardcoded paymentName 'giftcard'
        $this->assertSame('giftcard', $giftcard->paymentName());
    }
}
