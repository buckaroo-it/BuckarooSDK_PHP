# Going live

Before you switch to production:

1. Test your full flow in `test` mode, including the return page, the push handler and refunds.
2. Make sure your `pushURL` is publicly reachable over HTTPS and returns HTTP `200`. Buckaroo sends push messages from fixed IP addresses and ports, so make sure these are on your allow list. See [push messages](https://docs.buckaroo.io/docs/integration-push-messages) for the current list.
3. Activate the payment methods you need for your website in Plaza.
4. Switch the mode to `live`:

```php
$buckaroo = new \Buckaroo\BuckarooClient('STORE_KEY', 'SECRET_KEY', 'live');
```
