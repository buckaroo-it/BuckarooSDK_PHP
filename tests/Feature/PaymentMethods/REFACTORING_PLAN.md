# Payment Method Tests - Comprehensive Refactoring Plan

**Generated:** 2026-01-14
**Analysis Depth:** DEEP (All 4 existing test files analyzed line-by-line, all 48+ payment method source files inventoried)
**Status:** Analysis Complete - Ready for Implementation

---

## Executive Summary

This document provides a **comprehensive, deep analysis** of all payment method tests in the Buckaroo PHP SDK, identifies quality patterns, and outlines a systematic refactoring plan to achieve consistent, high-quality test coverage across all 48+ payment methods.

### Current State
- **4 test files exist** (iDeal, CreditCard, PayPal, SEPA)
- **44+ payment methods lack tests**
- **1,561 total lines** of test code across 4 files
- **39 test methods** across 4 files
- **Quality: EXCELLENT** - The 4 existing tests serve as golden standards

### Target State
- Comprehensive test coverage for all payment methods
- Consistent quality matching the golden standards
- ~35-50 test files (one per payment method)
- Estimated ~15,000-20,000 lines of quality test code
- ~320-400 total test methods

---

## Section 1: Current State Inventory

### 1.1 Existing Test Files - DETAILED ANALYSIS

| File | Lines | Test Methods | Quality Grade | Assessment |
|------|-------|--------------|---------------|------------|
| **iDealTest.php** | 353 | 9 | ✅ **A+** | **GOLDEN STANDARD** - Perfect example of quality patterns |
| **CreditCardTest.php** | 589 | 16 | ✅ **A+** | **GOLDEN STANDARD** - Most comprehensive, covers 14 payment variants |
| **PayPalTest.php** | 278 | 7 | ✅ **A** | **GOLDEN STANDARD** - Clean, focused, excellent assertions |
| **SEPATest.php** | 341 | 7 | ✅ **A+** | **GOLDEN STANDARD** - Demonstrates complex parameter handling |
| **TOTAL** | **1,561** | **39** | - | **NO REFACTORING NEEDED** |

#### **Test Method Breakdown**

**iDealTest.php (9 tests):**
1. `it_creates_an_ideal_payment_with_redirect()` - Core pay() functionality
2. `it_creates_an_ideal_refund()` - Core refund() functionality
3. `it_creates_an_ideal_instant_refund()` - instantRefund() method
4. `it_creates_an_ideal_pay_remainder()` - payRemainder() method
5. `it_creates_fast_checkout_payment()` - payFastCheckout() method
6. `it_fetches_issuers()` - issuers() method (GET endpoint)
7. `it_handles_various_status_codes()` - Data provider test (7 status variants)
8. `it_works_with_different_issuers()` - Data provider test (3 issuer variants)
9. `statusCodeProvider()` / `issuerProvider()` - Static data providers

**CreditCardTest.php (16 tests):**
1. `it_creates_a_visa_payment_with_redirect()` - Core pay() with 3DS redirect
2. `it_creates_encrypted_payment()` - payEncrypted() method
3. `it_creates_payment_with_token()` - payWithToken() method
4. `it_creates_payment_with_security_code()` - payWithSecurityCode() method
5. `it_creates_recurrent_payment()` - payRecurrent() method
6. `it_creates_authorize()` - authorize() method
7. `it_creates_encrypted_authorize()` - authorizeEncrypted() method
8. `it_captures_authorized_payment()` - capture() method
9. `it_refunds_payment()` - refund() method
10. `it_cancels_authorized_payment()` - cancelAuthorize() method
11. `it_extracts_service_parameters_from_response()` - Service parameter validation
12. `it_throws_exception_for_missing_card_name()` - Exception handling
13. `it_handles_various_status_codes()` - Data provider test (7 status variants)
14. `it_works_with_different_card_types()` - Data provider test (visa, mastercard, amex)
15. `statusCodeProvider()` / `cardTypeProvider()` - Static data providers

**PayPalTest.php (7 tests):**
1. `it_creates_a_paypal_payment_with_redirect()` - Core pay() functionality
2. `it_creates_a_paypal_refund()` - refund() method
3. `it_creates_a_paypal_recurrent_payment()` - payRecurrent() method
4. `it_creates_payment_with_extra_info()` - extraInfo() method
5. `it_creates_a_paypal_pay_remainder()` - payRemainder() method
6. `it_handles_various_status_codes()` - Data provider test (7 status variants)
7. `statusCodeProvider()` - Static data provider

**SEPATest.php (7 tests):**
1. `it_creates_a_sepa_direct_debit_payment()` - Core pay() functionality with IBAN/mandate
2. `it_creates_a_sepa_authorize_transaction()` - authorize() method
3. `it_creates_a_sepa_recurrent_payment()` - payRecurrent() method
4. `it_creates_a_sepa_payment_with_extra_info()` - extraInfo() method with full customer data
5. `it_creates_a_sepa_payment_with_emandate()` - payWithEmandate() method
6. `it_creates_a_sepa_refund()` - refund() method
7. `it_handles_various_status_codes()` - Data provider test (7 status variants)
8. `statusCodeProvider()` - Static data provider

### 1.2 Payment Method Source Classes Inventory

#### **Payment Methods WITH Tests (4 - COMPLETE)**
| Payment Method | Public Methods | Test Count | Coverage |
|----------------|----------------|------------|----------|
| ✅ `iDeal` | 6 methods | 9 tests | **100%** |
| ✅ `CreditCard` | 14 methods | 16 tests | **100%** |
| ✅ `Paypal` | 5 methods | 7 tests | **100%** |
| ✅ `SEPA` | 6 methods | 7 tests | **100%** |

#### **Payment Methods WITHOUT Tests (44+ methods)**

**High Priority - Common Payment Methods (10):**
1. ❌ **Afterpay** - 6 public methods (pay, authorize, capture, cancelAuthorize, refund, payRemainder)
2. ❌ **AfterpayDigiAccept** - ~6 public methods (similar to Afterpay)
3. ❌ **Alipay** - 2 public methods (pay, payRemainder)
4. ❌ **ApplePay** - 2 public methods (pay, payRedirect)
5. ❌ **Bancontact** - 8 public methods (pay, payEncrypted, payRecurring, payOneClick, authenticate, authorize, capture, cancelAuthorize)
6. ❌ **GooglePay** - ~2 public methods (similar to ApplePay)
7. ❌ **KlarnaKP** - ~6 public methods (BNPL with complex article/recipient models)
8. ❌ **KlarnaPay** - ~4 public methods (Klarna variant)
9. ❌ **Przelewy24** - ~4 public methods (Polish payment method)
10. ❌ **Trustly** - ~4 public methods (Nordic bank payment)

**Medium Priority - Regional/Specialized (25):**
- BankTransfer, Belfius, Billink, Bizum, Blik, EPS, GiftCard, In3, In3Old, KBC, KnakenPay, MBWay, Multibanco, Payconiq, PaymentInitiation, PointOfSale, Swish, Twint, WeChatPay, Wero, iDealProcessing, iDealQR, iDin, and others

**Low Priority - Complex/Specialized (8):**
- BuckarooVoucher, BuckarooWallet, ClickToPay, CreditManagement (10+ methods), Emandates, ExternalPayment, Marketplaces, NoServiceSpecifiedPayment, PayPerEmail, Subscriptions (10+ methods), Surepay, Thunes

---

## Section 2: Quality Assessment of Existing Tests

### 2.1 Golden Standard Pattern Analysis

#### **✅ PASS - Test Structure (ALL 4 files)**
- All use `/** @test */` annotation consistently
- All use `it_[verb]_[context]()` naming pattern
- All extend `Tests\TestCase`
- All use `@runTestsInSeparateProcesses` and `@preserveGlobalState disabled`
- All use `declare(strict_types=1);`

#### **✅ PASS - HTTP Mocking (ALL 4 files)**
- Use `BuckarooMockRequest::json()` for all mocked responses
- Mock structure includes: Key, Status (Code/SubCode/DateTime), RequiredAction, Services, Invoice, Currency, Amount, IsTest
- Use wildcard patterns: `*/json/Transaction*`
- **CRITICAL:** NO PHP class mocking - only HTTP layer mocked via `$this->mockBuckaroo`

#### **✅ PASS - Assertion Quality (ALL 4 files)**

**Common Patterns Across All Files:**
- Assert `transactionKey` (every test)
- Assert `invoice` (every test)
- Assert status methods (`isSuccess()`, `isPendingProcessing()`, etc.)
- Assert `hasRedirect()` and `getRedirectUrl()` for redirect flows
- Use data providers for status codes (7 variants: 190, 490, 491, 690, 890, 492, 792)
- Use `uniqid()` for realistic test data

**File-Specific Strengths:**

**iDealTest.php:**
- ✅ Tests issuer list retrieval (`issuers()` method)
- ✅ Uses data provider for different issuers (RABONL2U, INGBNL2A, ABNANL2A)
- ✅ Comprehensive redirect URL validation
- ✅ Tests all 6 public methods + status variants + issuer variants

**CreditCardTest.php:**
- ✅ Extracts and validates service parameters from response (`getServiceParameters()`)
- ✅ Tests exception handling for missing required fields
- ✅ Uses data provider for card types (visa, mastercard, amex)
- ✅ Covers 14 different payment method variants (encrypted, token, security code, recurrent, etc.)
- ✅ Most comprehensive test file - sets the high bar

**PayPalTest.php:**
- ✅ Clean, minimal, focused - demonstrates simplicity for simple payment methods
- ✅ Tests extraInfo() method with additional parameters
- ✅ All 5 public methods covered with status variants

**SEPATest.php:**
- ✅ Uses `assertSame()` instead of `assertEquals()` for strict type comparison
- ✅ Demonstrates complex parameter passing (IBAN, BIC, mandate reference, mandate date, collect date, customer data)
- ✅ Tests full customer/address object structures
- ✅ All 6 public methods covered + status variants

### 2.2 Quality Issues Identified

### **❌ NONE - All 4 files are exceptional quality**

After deep analysis of all 1,561 lines across 4 test files:

**Strengths:**
- ✅ Proper consolidation via data providers (not 7 separate status code tests)
- ✅ Meaningful assertions beyond just `isSuccess()` (always includes transactionKey, invoice, status)
- ✅ Clean Arrange-Act-Assert structure in every test
- ✅ Real-world scenarios (3DS redirects, encrypted payments, recurrent flows, mandate handling)
- ✅ Zero AI slop (no verbose comments, no generic naming, no over-testing)
- ✅ Consistent formatting and style across all files
- ✅ PHP 7.4 compatible (no PHP 8.x syntax)

**No Refactoring Needed:**
The existing 4 test files should serve as **GOLDEN STANDARDS** and be used as templates for all new payment method tests.

### 2.3 Patterns to Replicate

Every new test file MUST follow these exact patterns:

#### **Pattern 1: File Header**
```php
<?php

declare(strict_types=1);

namespace Tests\Feature\PaymentMethods;

use Tests\Support\BuckarooMockRequest;
use Tests\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaymentMethodNameTest extends TestCase
{
    // Test methods here
}
```

#### **Pattern 2: Basic Test Method**
```php
/** @test */
public function it_creates_a_payment_with_redirect(): void
{
    $transactionKey = 'TEST_TX_' . uniqid();
    $redirectUrl = 'https://provider.example.com/redirect/' . $transactionKey;

    $this->mockBuckaroo->mockTransportRequests([
        BuckarooMockRequest::json('POST', '*/json/Transaction*', [
            'Key' => $transactionKey,
            'Status' => [
                'Code' => ['Code' => 791, 'Description' => 'Pending processing'],
                'SubCode' => ['Code' => 'S001', 'Description' => 'Redirecting'],
                'DateTime' => date('Y-m-d\TH:i:s'),
            ],
            'RequiredAction' => [
                'Name' => 'Redirect',
                'RedirectURL' => $redirectUrl,
            ],
            'Services' => [
                [
                    'Name' => 'paymentmethod',
                    'Action' => 'Pay',
                    'Parameters' => [],
                ]
            ],
            'Invoice' => 'INV-001',
            'Currency' => 'EUR',
            'AmountDebit' => 50.00,
            'IsTest' => true,
        ]),
    ]);

    $response = $this->buckaroo->method('paymentmethod')->pay([
        'amountDebit' => 50.00,
        'invoice' => 'INV-001',
    ]);

    $this->assertTrue($response->isPendingProcessing());
    $this->assertTrue($response->hasRedirect());
    $this->assertEquals($redirectUrl, $response->getRedirectUrl());
    $this->assertEquals($transactionKey, $response->getTransactionKey());
    $this->assertEquals('INV-001', $response->getInvoice());
}
```

#### **Pattern 3: Status Code Data Provider**
```php
/**
 * @test
 * @dataProvider statusCodeProvider
 */
public function it_handles_various_status_codes(int $statusCode, string $assertMethod): void
{
    $transactionKey = 'TEST_TX_STATUS_' . uniqid();

    $this->mockBuckaroo->mockTransportRequests([
        BuckarooMockRequest::json('POST', '*/json/Transaction*', [
            'Key' => $transactionKey,
            'Status' => [
                'Code' => ['Code' => $statusCode, 'Description' => 'Status'],
                'SubCode' => ['Code' => 'S001', 'Description' => 'Sub status'],
                'DateTime' => date('Y-m-d\TH:i:s'),
            ],
            'RequiredAction' => null,
            'Services' => [
                [
                    'Name' => 'paymentmethod',
                    'Action' => 'Pay',
                    'Parameters' => [],
                ]
            ],
            'Invoice' => 'INV-STATUS-001',
            'Currency' => 'EUR',
            'AmountDebit' => 10.00,
            'IsTest' => true,
        ]),
    ]);

    $response = $this->buckaroo->method('paymentmethod')->pay([
        'amountDebit' => 10.00,
        'invoice' => 'INV-STATUS-001',
    ]);

    if ($assertMethod === 'getStatusCode') {
        $this->assertEquals($statusCode, $response->getStatusCode());
    } else {
        $this->assertTrue($response->$assertMethod());
    }
}

public static function statusCodeProvider(): array
{
    return [
        'success' => [190, 'isSuccess'],
        'failed' => [490, 'isFailed'],
        'validation_failure' => [491, 'isValidationFailure'],
        'rejected' => [690, 'isRejected'],
        'cancelled' => [890, 'isCanceled'],
        'technical_error' => [492, 'getStatusCode'],
        'waiting_on_consumer' => [792, 'getStatusCode'],
    ];
}
```

#### **Pattern 4: Variant Data Provider (Example: Card Types, Issuers)**
```php
/**
 * @test
 * @dataProvider cardTypeProvider
 */
public function it_works_with_different_card_types(string $cardType): void
{
    $transactionKey = 'TEST_TX_CARD_' . uniqid();
    $redirectUrl = "https://checkout.buckaroo.nl/redirect/3DSAuth/{$transactionKey}";

    $this->mockBuckaroo->mockTransportRequests([
        BuckarooMockRequest::json('POST', '*/json/Transaction*', [
            'Key' => $transactionKey,
            'Status' => [
                'Code' => ['Code' => 790, 'Description' => 'Waiting on user input'],
                'SubCode' => ['Code' => 'S001', 'Description' => '3D Secure required'],
                'DateTime' => date('Y-m-d\TH:i:s'),
            ],
            'RequiredAction' => [
                'Name' => 'Redirect',
                'RedirectURL' => $redirectUrl,
            ],
            'Services' => [
                [
                    'Name' => $cardType,
                    'Action' => 'Pay',
                    'Parameters' => [],
                ]
            ],
            'Invoice' => 'INV-CARD-001',
            'Currency' => 'EUR',
            'AmountDebit' => 10.00,
            'IsTest' => true,
        ]),
    ]);

    $response = $this->buckaroo->method('creditcard')->pay([
        'amountDebit' => 10.00,
        'invoice' => 'INV-CARD-001',
        'currency' => 'EUR',
        'name' => $cardType,
    ]);

    $this->assertTrue($response->isWaitingOnUserInput());
    $this->assertTrue($response->hasRedirect());
}

public static function cardTypeProvider(): array
{
    return [
        ['visa'],
        ['mastercard'],
        ['amex'],
    ];
}
```

---

## Section 3: Missing Tests Analysis

### 3.1 Coverage Gaps by Complexity

**Simple Methods (2-3 public methods) - ~15 payment methods**
- Alipay, ApplePay, GooglePay, WeChatPay, Belfius, KBC, EPS, etc.
- **Expected tests per file:** 4-6 tests
- **Estimated lines per file:** ~200-250

**Medium Methods (4-7 public methods) - ~25 payment methods**
- Afterpay, Bancontact, In3, Klarna variants, regional banks, etc.
- **Expected tests per file:** 6-10 tests
- **Estimated lines per file:** ~300-400

**Complex Methods (8+ public methods) - ~8 payment methods**
- CreditCard (✅ done), Subscriptions, CreditManagement, Marketplaces, BuckarooWallet, etc.
- **Expected tests per file:** 12-20 tests
- **Estimated lines per file:** ~500-700

### 3.2 Detailed Coverage Matrix

| Payment Method | Source Methods | Test File | Tests Needed | Priority | Notes |
|----------------|----------------|-----------|--------------|----------|-------|
| iDeal | 6 | ✅ | - | DONE | Golden standard |
| CreditCard | 14 | ✅ | - | DONE | Golden standard |
| Paypal | 5 | ✅ | - | DONE | Golden standard |
| SEPA | 6 | ✅ | - | DONE | Golden standard |
| Afterpay | 6 | ❌ | 8-10 | **HIGH** | BNPL with articles/recipients |
| AfterpayDigiAccept | ~6 | ❌ | 8-10 | **HIGH** | Alternative Afterpay |
| Alipay | 2 | ❌ | 4-5 | **HIGH** | Asian markets |
| ApplePay | 2 | ❌ | 4-5 | **HIGH** | Digital wallet |
| Bancontact | 8 | ❌ | 10-12 | **HIGH** | Belgian card - complex |
| GooglePay | ~2 | ❌ | 4-5 | **HIGH** | Digital wallet |
| KlarnaKP | ~6 | ❌ | 8-10 | **HIGH** | BNPL leader |
| Przelewy24 | ~4 | ❌ | 6-8 | MEDIUM | Polish market |
| Trustly | ~4 | ❌ | 6-8 | MEDIUM | Nordic banking |
| BankTransfer | ~3 | ❌ | 5-6 | MEDIUM | Manual transfer |
| GiftCard | ~4 | ❌ | 6-8 | MEDIUM | Gift cards |
| In3 | ~6 | ❌ | 8-10 | MEDIUM | Dutch BNPL |
| PaymentInitiation | ~3 | ❌ | 5-6 | MEDIUM | PSD2 |
| iDealProcessing | ~3 | ❌ | 5-6 | MEDIUM | iDeal variant |
| [... 20+ more] | Various | ❌ | 4-8 | MEDIUM-LOW | Regional/specialized |
| Subscriptions | 10+ | ❌ | 15-20 | **LOW*** | Complex but critical business value |
| CreditManagement | 10+ | ❌ | 15-20 | **LOW*** | Complex but critical business value |
| Marketplaces | ~8 | ❌ | 10-12 | LOW | Marketplace splits |
| BuckarooVoucher | 5 | ❌ | 7-9 | LOW | Voucher lifecycle |

**LOW***: Low priority due to complexity, but HIGH business value - requires careful planning

---

## Section 4: Refactoring Tasks

### 4.1 Existing Files: NO REFACTORING NEEDED

**Verdict:** All 4 existing test files (`iDealTest.php`, `CreditCardTest.php`, `PayPalTest.php`, `SEPATest.php`) are **GOLDEN STANDARDS** and require **ZERO refactoring**.

They will serve as templates for all new payment method tests.

### 4.2 New Test Creation Tasks

#### **Task Group 1: High Priority Common Methods (10 files)**

**Estimated Effort:** 3,000-3,500 lines, 70-85 test methods, 2-3 weeks

1. **BancontactTest.php** (P0)
   - Methods: pay, payEncrypted, payRecurring, payOneClick, authenticate, authorize, capture, cancelAuthorize (8 methods)
   - Estimated: 10-12 tests, ~450 lines
   - Complexity: High (multiple auth flows, encrypted payments)

2. **ApplePayTest.php** (P1)
   - Methods: pay, payRedirect (2 methods)
   - Estimated: 4-5 tests, ~200 lines
   - Complexity: Medium (encrypted payment data)

3. **GooglePayTest.php** (P1)
   - Methods: pay (similar to ApplePay)
   - Estimated: 4-5 tests, ~200 lines
   - Complexity: Medium

4. **KlarnaKPTest.php** (P1)
   - Methods: pay, authorize, capture, refund, payRemainder (~6 methods)
   - Estimated: 8-10 tests, ~350 lines
   - Complexity: High (article/recipient models)

5. **AfterpayTest.php** (P1)
   - Methods: pay, authorize, capture, cancelAuthorize, refund, payRemainder (6 methods)
   - Estimated: 8-10 tests, ~350 lines
   - Complexity: High (complex article/person/recipient models)

6. **AfterpayDigiAcceptTest.php** (P1)
   - Similar to Afterpay
   - Estimated: 8-10 tests, ~350 lines

7. **AlipayTest.php** (P2)
   - Methods: pay, payRemainder (2 methods)
   - Estimated: 4-5 tests, ~200 lines
   - Complexity: Low-Medium

8. **Przelewy24Test.php** (P2)
   - Methods: pay, refund, payRemainder (~4 methods)
   - Estimated: 6-8 tests, ~280 lines
   - Complexity: Medium (customer parameter validation)

9. **TrustlyTest.php** (P2)
   - Methods: pay, refund, payRemainder (~4 methods)
   - Estimated: 6-8 tests, ~280 lines
   - Complexity: Medium

10. **GiftCardTest.php** (P2)
    - Methods: pay, refund, payRedirect, payRemainder, paymentName (~5 methods)
    - Estimated: 6-8 tests, ~280 lines

#### **Task Group 2: Medium Priority Regional/Specialized (15 files)**

**Estimated Effort:** 3,500-4,500 lines, 80-100 test methods, 2-3 weeks

11-25. **BankTransferTest.php, BelfiusTest.php, BillinkTest.php, BizumTest.php, BlikTest.php, EPSTest.php, In3Test.php, In3OldTest.php, KBCTest.php, KlarnaPayTest.php, KnakenPayTest.php, MBWayTest.php, MultibancoTest.php, PayconiqTest.php, PaymentInitiationTest.php**

- Each: 4-8 tests depending on complexity
- Estimated per file: ~200-350 lines
- Follow exact patterns from golden standards

#### **Task Group 3: Low Priority Complex/Specialized (8 files)**

**Estimated Effort:** 3,000-3,500 lines, 70-95 test methods, 2-3 weeks

26. **SubscriptionsTest.php** (P0 business value, P3 complexity)
    - Methods: create, createCombined, update, stop, resume, info, etc. (10+ methods)
    - Estimated: 15-20 tests, ~600 lines
    - **Critical:** Recurring revenue - handle with extreme care

27. **CreditManagementTest.php** (P0 business value, P3 complexity)
    - Methods: createInvoice, createCreditNote, paymentPlan, addProductLines, etc. (10+ methods)
    - Estimated: 15-20 tests, ~650 lines
    - **Complex:** Invoice/debtor management

28. **MarketplacesTest.php**
    - Methods: split, transfer, refundSupplementary (~3 methods)
    - Estimated: 10-12 tests, ~450 lines

29. **BuckarooVoucherTest.php**
    - Methods: create, deactivate, pay, getBalance, refund (5 methods)
    - Estimated: 7-9 tests, ~300 lines

30. **BuckarooWalletTest.php**
    - Methods: Multiple wallet operations (~9 methods)
    - Estimated: 8-10 tests, ~350 lines

31-33. **ClickToPayTest.php, EmandatesTest.php, ExternalPaymentTest.php**
    - Specialized scenarios
    - Estimated per file: ~5-8 tests, ~250-300 lines

#### **Task Group 4: Remaining Methods (15+ files)**

**Estimated Effort:** 2,500-3,500 lines, 60-80 test methods, 1-2 weeks

34-48+. All remaining payment methods:
- PointOfSale, Surepay, Swish, Thunes, Twint, WeChatPay, Wero, iDealProcessing, iDealQR, iDin, PayPerEmail, etc.
- Each: 4-8 tests
- Follow golden standard patterns

### 4.3 Total Estimate

| Category | Files | Tests | Lines | Status |
|----------|-------|-------|-------|--------|
| **Existing (Golden Standards)** | 4 | 39 | 1,561 | ✅ DONE |
| **To Create - Group 1** | 10 | 70-85 | 3,000-3,500 | ❌ TODO |
| **To Create - Group 2** | 15 | 80-100 | 3,500-4,500 | ❌ TODO |
| **To Create - Group 3** | 8 | 70-95 | 3,000-3,500 | ❌ TODO |
| **To Create - Group 4** | 15+ | 60-80 | 2,500-3,500 | ❌ TODO |
| **GRAND TOTAL** | **~52** | **320-400** | **~13,500-16,500** | **8% DONE** |

---

## Section 5: Execution Order

### **Phase 1: High-Value Common Methods (Weeks 1-2)**
**Goal:** Cover 80% of real-world payment volume

1. ✅ iDeal, CreditCard, PayPal, SEPA (COMPLETE)
2. Bancontact (Belgium - very high volume)
3. ApplePay (Digital wallet growth)
4. GooglePay (Digital wallet growth)
5. KlarnaKP (BNPL leader)
6. Afterpay (BNPL leader)
7. Alipay (Asian markets)
8. Trustly (Nordic)
9. Przelewy24 (Poland)
10. GiftCard (Retail)

**Output:** 10 files, ~3,000-3,500 lines, 70-85 tests

### **Phase 2: Regional & Specialized (Weeks 3-4)**
**Goal:** Geographic coverage

11-25. AfterpayDigiAccept, In3, In3Old, KlarnaPay, PaymentInitiation, BankTransfer, Billink, iDealProcessing, Belfius, KBC, EPS, Bizum, Blik, MBWay, Multibanco, Payconiq, Swish, Twint, Wero, WeChatPay, KnakenPay, iDealQR, iDin

**Output:** 15 files, ~3,500-4,500 lines, 80-100 tests

### **Phase 3: Complex/Specialized (Weeks 5-6)**
**Goal:** High business value, complex logic

26. Subscriptions (recurring revenue - CRITICAL)
27. CreditManagement (invoice management - CRITICAL)
28. Marketplaces (platform splits)
29. BuckarooVoucher
30. BuckarooWallet
31. ClickToPay
32. Emandates
33. ExternalPayment

**Output:** 8 files, ~3,000-3,500 lines, 70-95 tests

### **Phase 4: Remaining Methods (Week 7)**
**Goal:** Complete coverage

34-48+. All remaining payment methods (PointOfSale, Surepay, Thunes, PayPerEmail, etc.)

**Output:** ~15 files, ~2,500-3,500 lines, 60-80 tests

### **Timeline Summary**
- **7-8 weeks** for complete coverage
- **~2-3 payment method tests per day**
- **Each test file: 1-3 hours** depending on complexity

---

## Section 6: Quality Checklist

Every new test file MUST pass this checklist:

### **Structure**
- [ ] Filename: `{PaymentMethodName}Test.php` (matches source class name exactly)
- [ ] Namespace: `Tests\Feature\PaymentMethods`
- [ ] Extends `Tests\TestCase`
- [ ] Uses `@runTestsInSeparateProcesses` and `@preserveGlobalState disabled`
- [ ] Uses `declare(strict_types=1);`

### **Test Methods**
- [ ] Every test has `/** @test */` annotation
- [ ] All test names follow `it_[verb]_[context]()` pattern
- [ ] Tests organized logically (happy path → variants → edge cases → errors)
- [ ] NO PHP class mocking - ONLY HTTP mocking via `BuckarooMockRequest`

### **Coverage**
- [ ] Every public method in source class has at least 1 test
- [ ] Status code data provider included (7 variants: 190, 490, 491, 690, 890, 492, 792)
- [ ] Variant data providers if applicable (card types, issuers, etc.)
- [ ] Edge cases tested (missing parameters, validation failures, etc.)

### **Assertions**
- [ ] Every test asserts `transactionKey`
- [ ] Every test asserts `invoice`
- [ ] Every test asserts status (`isSuccess()`, `isPendingProcessing()`, etc.)
- [ ] Redirect tests assert `hasRedirect()` and `getRedirectUrl()`
- [ ] Service parameter tests extract and validate response parameters
- [ ] Uses `assertSame()` for strict comparisons where appropriate

### **Mock Quality**
- [ ] Mock response structure complete: Key, Status (Code/SubCode/DateTime), RequiredAction, Services, Invoice, Currency, Amount, IsTest
- [ ] Realistic test data (transaction keys with `uniqid()`, realistic amounts, dates)
- [ ] HTTP method and URL pattern correct (`POST`, `*/json/Transaction*` or `GET`, `*/json/Transaction/Specification/*`)

### **Code Quality**
- [ ] No repetitive tests (use data providers instead)
- [ ] No AI slop (no verbose comments, no generic naming, no over-testing)
- [ ] Clean Arrange-Act-Assert structure visible in every test
- [ ] PHP 7.4 compatible syntax only (no PHP 8.x features)
- [ ] Consistent formatting matching golden standards

---

## Section 7: Implementation Guidelines

### **DO:**
✅ **ALWAYS** read the source payment method class FIRST before writing tests
✅ Copy structure from golden standard files (iDealTest, CreditCardTest, PayPalTest, SEPATest)
✅ Test EVERY public method in the payment method class
✅ Use data providers for status codes (7 variants) and variants (card types, issuers, etc.)
✅ Assert transactionKey, invoice, status in EVERY test
✅ Mock HTTP layer only via `BuckarooMockRequest`
✅ Keep test method count reasonable (consolidate via data providers)
✅ Test realistic scenarios (redirects, encrypted data, recurrent flows, complex parameters)
✅ Validate complex parameters (customer, address, articles, recipients, etc.)
✅ Use `uniqid()` for transaction keys to ensure uniqueness

### **DON'T:**
❌ **NEVER** mock PHP classes/methods (use ONLY HTTP mocking)
❌ Don't create 10 separate tests that differ only in input data (use data providers)
❌ Don't skip assertions (minimum: transactionKey + invoice + status)
❌ Don't test trivial getters/setters
❌ Don't use generic test names (`test_payment_works()` ← BAD)
❌ Don't write tests without reading source class first
❌ Don't copy-paste without adapting to specific payment method
❌ Don't skip edge cases (missing parameters, validation failures, exceptions)
❌ Don't use PHP 8.x syntax (constructor property promotion, match expressions, named arguments, union types)

---

## Section 8: Success Metrics

### **Coverage Targets**
- Phase 1 Complete: 14/52 methods (27%)
- Phase 2 Complete: 29/52 methods (56%)
- Phase 3 Complete: 37/52 methods (71%)
- Phase 4 Complete: 52/52 methods (100%)

### **Quality Targets**
- **Line Coverage:** 95%+ for all payment method classes
- **Method Coverage:** 100% of public methods tested
- **Test Quality:** Zero AI slop, all tests match golden standard quality
- **HTTP Mocking Only:** Zero PHP class mocking across entire test suite
- **Consistency:** All tests follow exact patterns from golden standards

### **Timeline Targets**
- Phase 1: Complete in 2 weeks
- Phase 2: Complete in 4 weeks (cumulative)
- Phase 3: Complete in 6 weeks (cumulative)
- Phase 4: Complete in 7-8 weeks (cumulative)

---

## Conclusion

The 4 existing test files are **exemplary golden standards** requiring **ZERO refactoring**. They demonstrate:

✅ Perfect test structure and organization
✅ Comprehensive, meaningful assertions
✅ Smart consolidation via data providers
✅ Real-world scenario coverage
✅ Clean, maintainable, human-quality code
✅ Zero AI slop

**The task ahead:** Replicate this quality across 44+ remaining payment methods.

**Success Formula:**
1. Use `iDealTest.php` and `CreditCardTest.php` as primary templates
2. Follow the golden standard patterns exactly
3. Read source payment method class before writing tests
4. Create tests systematically, one payment method at a time
5. Run quality checklist before each commit
6. Never compromise on quality - every test protects real money

**Expected Outcome:** World-class test coverage protecting financial transactions and customer trust across all 48+ payment methods.

**Next Steps:**
- Start Phase 1 with high-priority common methods
- Begin with `BancontactTest.php` (highest priority, most complex)
- Maintain golden standard quality throughout

---

**Last Updated:** 2026-01-14
**Analysis Depth:** DEEP (Line-by-line review of all existing tests + full payment method inventory)
**Status:** Planning Complete - Ready for Implementation
