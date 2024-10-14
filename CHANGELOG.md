# Changelog

All notable changes to this project will be documented in this file.

---

## [Released]

## [1.14.0]
- BA-333 Add Belgium banks for the payment method PayByBank.
- BA-350 Push URL is set as a required parameter, but it should not be required when you want to follow the plaza Push settings.
- BA-383 Remove is_callable check for ServiceList parameters.
- BA-370 In some cases the PUSH messages are not being processed properly when a “/” is included in the name.

## [1.13.0]
- BA-269 Add iDEAL Snel Bestellen (Fast checkout)
- BA-278 Add payment method: Blik
- BA-276 Remove payment method: CreditClick

## [1.12.0]
- BA-252 Remove payment method: Tinka
- BA-239 - Fix: It is not possible to override the version withing requests
- BA-243 - Fix: iDEAL Processing not working properly, iDEAL processing is now a separate method.

## [1.11.0]
- BA-134 Add default Stdout Logger
- BP-3287 Add more debtor information for Buckaroo Subscriptions
- New payment method: Knaken Settle
- Bugfix: In3 update phone adapter key

## [1.10.0]
- BP-3189 Add "GetActiveSubscription" to retrieve all Buckaroo subscriptions
- BP-3290 Add fix for older Guzzlehttp version (v5)
- BP-3179 Add support for "External Payments"

## [1.9.0]
- BP-2873 Add "email" and "lastname" parameters for giftcard refunds
- BP-3034 Add payment method "MB WAY"
- BP-3020 Add payment method "Multibanco"
- BP-3009 PHP SDK defaults to testtransaction when left empty
- Able to retrieve issuers for PayByBank

## [1.8.1]
- BP-2912 rename IDin to iDin (#145) Contributed by @roelvanhintum

## [1.8.0]
- BP-2698 - Add example: PayByBank
- BP-2776 - Hotfix: Trustly payremainder adapter applied
- BP-2797 Add possibility to change Channel header
- Fixing dynamic property in Culture header
- Hotfix - default value in software header & additionalParameter fix
- Additional parameter fix
- Hotfix - HTTPACCEPTLANGUAGE should not be configure as culture
- Hotfix: additional parameters
- New In3 payment method

## [1.7.0]
- BP-2461 - Add the correct ModuleVersion and PlatformName
- BP-2688 - UPDATE README.md file on Github
- BP-2650 - Culture was not working correctly
- BP-2417 - Added Thunes
- BP-2507 - Add Payment initiation
- BP-2511 - Ippies is not a Giftcard
- BP-2543 - Add (Bancontact) Deferred Sales
- BP-2617 - Allow CustomParameters
- BP-2685 - ShippingCosts is missing for Riverty/AfterPay old
- BP-2460 - Add the correct ModuleVersion
- Ability to fill in software header
- Able to pass in a config object into the constructor
- Fixing wrong name in additional parameter
- Authenticate method refers to authorize
- Push validator fix

## [1.6.0]
- BP-2404 Feature/8.2 support
- BP-2509 Add Instant refunds,no service payment refactor
- BP-2527 Batch functionality
- Update ideal qr test
- Update Transaction Comment
- Created new PayablePaymentMethod for when no service is specified
- Remove method paynoservice
- Inlcude monolog version 3
- Include ramsey version 4
- Add missing property
- Add PayRemainder in KlarnaPay
- Pay remainder missing for some methods
- Add PayRemainder to Alipay
- Add PayRemainder to Paypal
- Add PayRemainder to Payconiq
- Add PayRemainder to Afterpay
- Add PayRemainder to Trustly
- Add PayRemainder to Przelewy24
- Add PayRemainder to Belfius
- Add PayRemainder to EPS
- Add transaction batch endpoint

## [1.5.2]
- Downgrade Ramsey UUID package to version 3 in order to support PHP 7.4

## [1.5.1]
- BP-2485 Bugfix in generating nonce, duplicated nonce can occured when there is a large amount of transactions.

## [1.5.0]
- BP-2351 Bugfix in transaction response in sub status code
- Subscription example updated.
- Github action added for PSR12 and PHPlint validation. Contributed by @avido
- iDeal QR Test updated.
- BP-2404 PHP 8.2 support

## [1.4.0]
- Fixed an issue with getting issuers for iDEAL. Reported by @reflie-nxte
- Add Bancontact alias and fixing typo in method name
- When response data is not set return null
- BP-2257 Payment method Request to pay removed
- BP-2285 BP-Add missing subscriptions parameters
- BP-2345 Add missing parameters in iDEAL QR

## [1.3.0]
- Add parameter retrieval in replyhandler, check lower & upper string
- Remove hardcoded iDEAL banks and use api call to retrieve the latest available iDEAL banks
- Add html_entity_decode for the validation checker
- Fixed combined subscription test
- Update Debtor code
- Fix additional parameters
- Add missing cardnumber & pin parameter in giftcard
- Check more condition on json return or push
- Php Clean code format PSR-12
- Add payRemainderEncrypted to creditcard
- Add culture to default payload
- Use TransactionResponse when retrieving the transation status Report by @avido in https://github.com/buckaroo-it/BuckarooSDK_PHP/issues/71
- Update rate_plans node to ratePlans for subscription method

## [1.2.0]
- Add cancelAuthorize method to billink
- Bugfix billink
- Remove Careof in Klarna KP
- Add missing methods in afterpaydigiaccept & partial refund in afterpay
- Add missing parameter and add cancelauthorize
- BP-2088 Consistent naming for VAT in Credit Management
- Added new payment method Buckaroo voucher
- Add ApplePay payRedirect method + example fix
- All examples updated
- Update iDeal banks
- Fix missing FashionChequePin

## [1.1.2]
- Add missing service parameters to Giftcard payment
- BP-2130 Examples updated. 
- Cast getAmount in TransactionResponse a string.
- ApplePay redirect method fixed.
- New payment method Buckaroo Voucher added.
- iDEAL banks list updated.

## [1.1.1]
- BP-2061 Signature bugfix
- BP-2059 Add CancelAuthorize for Creditcards & Missing APIs parameters

## [1.0.1]
- Add missing methods to different payment providers.
- Support lower and/or upper case reply and push response. Report by @antonboutkam https://github.com/buckaroo-it/BuckarooSDK_PHP/issues/36
- Improve reply and push validation. Report by @antonboutkam https://github.com/buckaroo-it/BuckarooSDK_PHP/issues/36
- Retrieve transaction status. Report by @antonboutkam in https://github.com/buckaroo-it/BuckarooSDK_PHP/issues/37
- ReplyHandler is able to retrieve the given data.
- Giftcard redirect method & review update
- Moved vlucas/phpdotenv to dev-dependency. Report by @ederuiter https://github.com/buckaroo-it/BuckarooSDK_PHP/issues/32

## [1.0.0]
- Initial release.