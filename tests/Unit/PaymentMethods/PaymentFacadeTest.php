<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods;

use Buckaroo\Exceptions\BuckarooException;
use Buckaroo\PaymentMethods\CreditCard\CreditCard;
use Buckaroo\PaymentMethods\iDeal\iDeal;
use Buckaroo\PaymentMethods\Interfaces\Combinable;
use Buckaroo\PaymentMethods\NoServiceSpecifiedPayment\NoServiceSpecifiedPayment;
use Buckaroo\PaymentMethods\PaymentFacade;
use Buckaroo\PaymentMethods\PaymentMethod;
use Buckaroo\PaymentMethods\Paypal\Paypal;
use Buckaroo\Transaction\Client;
use Tests\Support\BuckarooMockRequest;
use Tests\Support\TestHelpers;
use Tests\TestCase;

class PaymentFacadeTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->buckaroo->client();
    }

    public function test_it_creates_facade_with_payment_method(): void
    {
        $facade = new PaymentFacade($this->client, 'ideal');

        $this->assertInstanceOf(PaymentFacade::class, $facade);
        $this->assertInstanceOf(PaymentMethod::class, $facade->paymentMethod());
        $this->assertInstanceOf(iDeal::class, $facade->paymentMethod());
    }

    public function test_it_creates_facade_with_null_payment_method(): void
    {
        $facade = new PaymentFacade($this->client, null);

        $this->assertInstanceOf(NoServiceSpecifiedPayment::class, $facade->paymentMethod());
    }

    public function test_it_returns_payment_method(): void
    {
        $facade = new PaymentFacade($this->client, 'paypal');

        $paymentMethod = $facade->paymentMethod();

        $this->assertInstanceOf(Paypal::class, $paymentMethod);
        $this->assertSame('paypal', $paymentMethod->paymentName());
    }

    public function test_manually_returns_self(): void
    {
        $facade = new PaymentFacade($this->client, 'ideal');

        $result = $facade->manually();

        $this->assertSame($facade, $result);
    }

    public function test_manually_sets_flag_on_payment_method(): void
    {
        $this->mockBuckaroo->mockTransportRequests([]);

        $facade = new PaymentFacade($this->client, 'ideal');

        // After calling manually(), the payment method should return itself
        // instead of making an HTTP request
        $result = $facade->manually()->pay([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-001',
            'issuer' => 'ABNANL2A',
        ]);

        // When manually is set, the payment method returns itself
        $this->assertInstanceOf(PaymentMethod::class, $result);
    }

    public function test_combine_returns_self(): void
    {
        $facade = new PaymentFacade($this->client, 'ideal');

        $result = $facade->combine([]);

        $this->assertSame($facade, $result);
    }

    public function test_combine_accepts_combinable_payment(): void
    {
        $facade = new PaymentFacade($this->client, 'ideal');
        $creditCardFacade = new PaymentFacade($this->client, 'creditcard');

        // Get the credit card payment method which implements Combinable
        $creditCardPayment = $creditCardFacade->manually()->pay([
            'amountDebit' => 5.00,
            'invoice' => 'TEST-001',
            'name' => 'visa',
        ]);

        // CreditCard implements Combinable, so it can be combined
        $this->assertInstanceOf(Combinable::class, $creditCardPayment);

        $result = $facade->combine($creditCardPayment);

        $this->assertSame($facade, $result);
    }

    public function test_combine_accepts_array_of_combinable_payments(): void
    {
        $facade = new PaymentFacade($this->client, 'ideal');

        // Create multiple combinable payments
        $creditCardFacade1 = new PaymentFacade($this->client, 'creditcard');
        $payment1 = $creditCardFacade1->manually()->pay([
            'amountDebit' => 5.00,
            'invoice' => 'TEST-001',
            'name' => 'visa',
        ]);

        $creditCardFacade2 = new PaymentFacade($this->client, 'creditcard');
        $payment2 = $creditCardFacade2->manually()->pay([
            'amountDebit' => 5.00,
            'invoice' => 'TEST-002',
            'name' => 'mastercard',
        ]);

        $result = $facade->combine([$payment1, $payment2]);

        $this->assertSame($facade, $result);
    }

    public function test_combine_ignores_non_combinable_payment(): void
    {
        $facade = new PaymentFacade($this->client, 'ideal');

        // Passing a non-Combinable value should not throw an error
        $result = $facade->combine('not-a-combinable');

        $this->assertSame($facade, $result);
    }

    public function test_magic_call_delegates_to_payment_method(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'POST',
                '*/Transaction/',
                TestHelpers::successResponse([
                    'Key' => TestHelpers::generateTransactionKey(),
                ])
            ),
        ]);

        $facade = new PaymentFacade($this->client, 'ideal');

        $response = $facade->pay([
            'amountDebit' => 10.00,
            'invoice' => 'TEST-001',
            'issuer' => 'ABNANL2A',
        ]);

        $this->assertTrue($response->isSuccess());
    }

    public function test_magic_call_throws_exception_for_undefined_method(): void
    {
        $facade = new PaymentFacade($this->client, 'ideal');

        $this->expectException(BuckarooException::class);
        $this->expectExceptionMessage('Payment method nonExistentMethod on payment ideal you requested does not exist.');

        $facade->nonExistentMethod([]);
    }

    public function test_set_service_version_via_magic_call(): void
    {
        $facade = new PaymentFacade($this->client, 'ideal');

        $result = $facade->setServiceVersion(2);

        $this->assertSame($facade, $result);
        $this->assertSame(2, $facade->paymentMethod()->serviceVersion());
    }

    public function test_magic_call_with_empty_arguments(): void
    {
        $this->mockBuckaroo->mockTransportRequests([
            BuckarooMockRequest::json(
                'GET',
                '*/Transaction/Specification/ideal*',
                [
                    'Services' => [
                        [
                            'Name' => 'ideal',
                            'Version' => 2,
                            'ActionDescriptions' => [
                                [
                                    'Name' => 'Pay',
                                    'RequestParameters' => [
                                        [
                                            'Name' => 'issuer',
                                            'DataType' => 'list',
                                            'ListItemDescriptions' => [
                                                ['Value' => 'ABNANL2A', 'Description' => 'ABN AMRO'],
                                                ['Value' => 'INGBNL2A', 'Description' => 'ING'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            ),
        ]);

        $facade = new PaymentFacade($this->client, 'ideal');

        // issuers() doesn't require arguments
        $response = $facade->issuers();

        $this->assertIsArray($response);
    }

    public function test_method_chaining(): void
    {
        $this->mockBuckaroo->mockTransportRequests([]);

        $facade = new PaymentFacade($this->client, 'ideal');

        // Test that methods can be chained
        $result = $facade
            ->manually()
            ->setServiceVersion(2)
            ->pay([
                'amountDebit' => 10.00,
                'invoice' => 'TEST-001',
                'issuer' => 'ABNANL2A',
            ]);

        $this->assertInstanceOf(PaymentMethod::class, $result);
    }

    public function test_facade_created_via_buckaroo_client_method(): void
    {
        $facade = $this->buckaroo->method('ideal');

        $this->assertInstanceOf(PaymentFacade::class, $facade);
        $this->assertInstanceOf(iDeal::class, $facade->paymentMethod());
    }

    public function test_different_payment_methods_via_facade(): void
    {
        $idealFacade = new PaymentFacade($this->client, 'ideal');
        $paypalFacade = new PaymentFacade($this->client, 'paypal');
        $creditCardFacade = new PaymentFacade($this->client, 'creditcard');

        $this->assertInstanceOf(iDeal::class, $idealFacade->paymentMethod());
        $this->assertInstanceOf(Paypal::class, $paypalFacade->paymentMethod());
        $this->assertInstanceOf(CreditCard::class, $creditCardFacade->paymentMethod());
    }
}
