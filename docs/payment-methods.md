# Payment methods

Pass one of these service codes to `$buckaroo->method('...')`. Each method has its own payload fields. The [`example/`](../example/) folder has a runnable sample for each method, and the [API reference](https://docs.buckaroo.io/reference) describes all parameters.

| Method | Service code | Example |
|---|---|---|
| iDEAL | `ideal` | [ideal.php](../example/transactions/ideal.php) |
| iDEAL QR | `ideal_qr` | [ideal_qr.php](../example/transactions/ideal_qr.php) |
| Credit & debit cards | `creditcard` (or the brand: `visa`, `mastercard`, `amex`, …) | [creditcard.php](../example/transactions/creditcard.php) |
| PayPal | `paypal` | [paypal.php](../example/transactions/paypal.php) |
| Bancontact | `bancontact` | [bancontact.php](../example/transactions/bancontact.php) |
| Apple Pay | `applepay` | [applepay.php](../example/transactions/applepay.php) |
| Google Pay | `googlepay` | [googlepay.php](../example/transactions/googlepay.php) |
| Click to Pay | `clicktopay` | [clicktopay.php](../example/transactions/clicktopay.php) |
| PayByBank | `paybybank` | [payment_initiation.php](../example/transactions/payment_initiation.php) |
| SEPA Direct Debit | `sepadirectdebit` | [sepa.php](../example/transactions/sepa.php) |
| Bank transfer | `transfer` | [transfer.php](../example/transactions/transfer.php) |
| Klarna | `klarna` / `klarnakp` | [klarna.php](../example/transactions/klarna.php) / [klarnakp.php](../example/transactions/klarnakp.php) |
| Riverty | `afterpay` | [afterpay.php](../example/transactions/afterpay.php) |
| In3 | `in3` | [in3.php](../example/transactions/in3.php) |
| Billink | `billink` | [billink.php](../example/transactions/billink.php) |
| Gift cards | `giftcard` | [giftcards.php](../example/transactions/giftcards.php) |
| EPS | `eps` | [eps.php](../example/transactions/eps.php) |
| Przelewy24 | `przelewy24` | [przelewy24.php](../example/transactions/przelewy24.php) |
| Blik | `blik` | [blik.php](../example/transactions/blik.php) |
| Trustly | `trustly` | [trustly.php](../example/transactions/trustly.php) |
| Twint | `twint` | [twint.php](../example/transactions/twint.php) |
| Swish | `swish` | [swish.php](../example/transactions/swish.php) |
| MB WAY | `mbway` | [mbway.php](../example/transactions/mbway.php) |
| Multibanco | `multibanco` | [multibanco.php](../example/transactions/multibanco.php) |
| Bizum | `bizum` | [bizum.php](../example/transactions/bizum.php) |
| Wero | `wero` | [wero.php](../example/transactions/wero.php) |
| Payconiq | `payconiq` | [payconiq.php](../example/transactions/payconiq.php) |
| KBC | `kbc` | [kbc.php](../example/transactions/kbc.php) |
| Belfius | `belfius` | [belfius.php](../example/transactions/belfius.php) |
| Alipay | `alipay` | [alipay.php](../example/transactions/alipay.php) |
| WeChat Pay | `wechatpay` | [wechat.php](../example/transactions/wechat.php) |
| Point of Sale | `pospayment` | [pos.php](../example/transactions/pos.php) |

**Additional services:** [Subscriptions](../example/additional_services/subscriptions.php) · [Credit Management](../example/additional_services/credit_management.php) · [Pay per Email](../example/additional_services/pay_per_email.php) · [Marketplaces](../example/additional_services/marketplaces.php) · [iDIN](../example/additional_services/idin.php) · [Buckaroo Wallet](../example/additional_services/wallets.php) · [Buckaroo Voucher](../example/additional_services/voucher.php) · [SurePay](../example/additional_services/surepay.php)

## Running the examples

The scripts in [`example/`](../example/) read your credentials from a `.env` file and need the repository's dev dependencies:

```bash
git clone https://github.com/buckaroo-it/BuckarooSDK_PHP.git
cd BuckarooSDK_PHP
composer install
cp .env.example .env    # then fill in BPE_WEBSITE_KEY and BPE_SECRET_KEY
cd example/transactions
php ideal.php
```
