# Changelog

All notable changes to this project will be documented in this file.

---

## [Released]

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