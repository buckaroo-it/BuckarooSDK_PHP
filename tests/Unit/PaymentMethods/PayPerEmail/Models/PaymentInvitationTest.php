<?php

declare(strict_types=1);

namespace Tests\Unit\PaymentMethods\PayPerEmail\Models;

use Buckaroo\PaymentMethods\PayPerEmail\Models\PaymentInvitation;
use Buckaroo\PaymentMethods\PayPerEmail\Service\ParameterKeys\AttachmentAdapter;
use Buckaroo\PaymentMethods\PayPerEmail\Service\ParameterKeys\CustomerAdapter;
use Buckaroo\PaymentMethods\PayPerEmail\Service\ParameterKeys\EmailAdapter;
use Tests\TestCase;

class PaymentInvitationTest extends TestCase
{
    /** @test */
    public function it_sets_customer_from_array(): void
    {
        $invitation = new PaymentInvitation([
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ]);

        $customer = $invitation->customer();

        $this->assertInstanceOf(CustomerAdapter::class, $customer);
    }

    /** @test */
    public function it_returns_customer_without_parameter(): void
    {
        $invitation = new PaymentInvitation([
            'customer' => [
                'firstName' => 'Jane',
                'lastName' => 'Smith',
            ],
        ]);

        $customer = $invitation->customer();
        $this->assertInstanceOf(CustomerAdapter::class, $customer);

        $sameCustomer = $invitation->customer(null);
        $this->assertSame($customer, $sameCustomer);
    }

    /** @test */
    public function it_sets_email_from_string(): void
    {
        $invitation = new PaymentInvitation([
            'email' => 'test@example.com',
        ]);

        $email = $invitation->email();

        $this->assertInstanceOf(EmailAdapter::class, $email);
    }

    /** @test */
    public function it_returns_email_without_parameter(): void
    {
        $invitation = new PaymentInvitation([
            'email' => 'john@doe.com',
        ]);

        $email = $invitation->email();
        $this->assertInstanceOf(EmailAdapter::class, $email);

        $sameEmail = $invitation->email(null);
        $this->assertSame($email, $sameEmail);
    }

    /** @test */
    public function it_sets_attachments_from_array(): void
    {
        $invitation = new PaymentInvitation([
            'attachments' => [
                [
                    'name' => 'invoice.pdf',
                    'content' => base64_encode('PDF content'),
                ],
                [
                    'name' => 'terms.pdf',
                    'content' => base64_encode('Terms content'),
                ],
            ],
        ]);

        $attachments = $invitation->attachments();

        $this->assertIsArray($attachments);
        $this->assertCount(2, $attachments);
        $this->assertInstanceOf(AttachmentAdapter::class, $attachments[0]);
        $this->assertInstanceOf(AttachmentAdapter::class, $attachments[1]);
    }

    /** @test */
    public function it_returns_empty_attachments_array_without_parameter(): void
    {
        $invitation = new PaymentInvitation([]);

        $attachments = $invitation->attachments();

        $this->assertIsArray($attachments);
        $this->assertEmpty($attachments);
    }

    /** @test */
    public function it_sets_all_properties_together(): void
    {
        $invitation = new PaymentInvitation([
            'merchantSendsEmail' => true,
            'expirationDate' => '2024-02-15',
            'paymentMethodsAllowed' => 'ideal,creditcard',
            'customer' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
            'email' => 'test@example.com',
            'attachments' => [
                ['name' => 'invoice.pdf', 'content' => base64_encode('content')],
            ],
        ]);

        $this->assertInstanceOf(CustomerAdapter::class, $invitation->customer());
        $this->assertInstanceOf(EmailAdapter::class, $invitation->email());
        $this->assertCount(1, $invitation->attachments());
    }
}
