# Changelog

All notable changes to this project will be documented in this file.

---

## [Released]

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