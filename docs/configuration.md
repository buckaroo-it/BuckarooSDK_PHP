# Configuration

This guide covers creating the client, checking your credentials, advanced configuration, environment variables and logging.

## Creating the client

You can find your Store key and Secret key under [API credentials in Buckaroo Plaza](https://plaza.buckaroo.nl/Configuration/Merchant/ApiKeys). The third argument is the mode: `test` while developing, `live` in production. It defaults to `test`. In test mode, no real money is moved.

```php
require __DIR__ . '/vendor/autoload.php';

$buckaroo = new \Buckaroo\BuckarooClient('STORE_KEY', 'SECRET_KEY', 'test');
```

> [!TIP]
> Keep your Secret key out of version control. Load both keys from environment variables instead — the repository ships an [`.env.example`](https://github.com/buckaroo-it/BuckarooSDK_PHP/blob/master/.env.example) to start from.

## Checking your credentials

Use `confirmCredential()` to check that the keys are valid, for example in a health check or an admin "test connection" button:

```php
if (! $buckaroo->confirmCredential()) {
    throw new RuntimeException('Invalid Buckaroo credentials');
}
```

## Advanced configuration

To set defaults such as currency, culture or HTTP timeouts, pass a `Config` object instead of the keys:

```php
use Buckaroo\BuckarooClient;
use Buckaroo\Config\DefaultConfig;

$config = new DefaultConfig(
    'STORE_KEY',
    'SECRET_KEY',
    'test',                                   // mode: 'test' or 'live'
    'EUR',                                    // currency
    'https://example.com/buckaroo/return',    // returnURL
    'https://example.com/buckaroo/cancel',    // returnURLCancel
    'https://example.com/buckaroo/push',      // pushURL
    'My Shop',                                // platformName
    '1.0.0',                                  // platformVersion
    'My Company',                             // moduleSupplier
    'my-shop-buckaroo',                       // moduleName
    '1.0.0',                                  // moduleVersion
    'nl-NL',                                  // culture (language of the payment pages)
    'Web',                                    // channel
    null,                                     // logger (see "Logging")
    30,                                       // timeout in seconds
    10                                        // connect timeout in seconds
);

$buckaroo = new BuckarooClient($config);
```

## Environment variables

If any of these variables are set in `$_ENV`, the SDK uses them. **They take precedence over the values passed to the constructor.**

| Variable | Purpose | Default |
|---|---|---|
| `BPE_MODE` | `test` or `live` | `test` |
| `BPE_CURRENCY_CODE` | Default currency | `EUR` |
| `BPE_RETURN_URL` | Default return URL | — |
| `BPE_RETURN_URL_CANCEL` | Default cancel URL | — |
| `BPE_PUSH_URL` | Default push URL | — |
| `BPE_HTTP_TIMEOUT` | Request timeout (seconds) | — |
| `BPE_HTTP_CONNECT_TIMEOUT` | Connect timeout (seconds) | — |

## Logging

Attach a logger to see the requests and responses. The SDK includes a Monolog observer that writes to `stdout`:

```php
$buckaroo->attachLogger(new \Buckaroo\Handlers\Logging\Observers\Monolog());
```

To send the logs to your own logger, implement `Buckaroo\Handlers\Logging\Observer` and pass it to `attachLogger()`.
