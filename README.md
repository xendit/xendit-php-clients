# Xendit API PHP Client

This library is the abstraction of Xendit API for access from applications written with PHP.

-   [Documentation](#documentation)
-   [Installation](#installation)
-   [Usage](#usage)
-   [Methods' Signature and Examples](#methods-signature-and-examples)
    -   [Balance](#balance)
        -   [Get Balance](#get-balance)
    -   [Cards](#cards)
        -   [Create Charge](#create-charge)
        -   [Reverse Authorization](#reverse-authorization)
        -   [Capture Charge](#capture-charge)
        -   [Get Charge](#get-charge)
        -   [Create Refund](#create-refund)
    -   [Cardless Credit](#cardless-credit)
        -   [Create Cardless Credit Payment](#create-cardless-credit-payment)
        -   [Calculate Payment Types](#calculate-payment-types)
    -   [Disbursements](#disbursements)
        -   [Create Disbursement](#create-disbursement)
        -   [Create Batch Disbursement](#create-batch-disbursement)
        -   [Get Disbursement by ID](#get-disbursement-by-id)
        -   [Get Disbursement by External ID](#get-disbursement-by-external-id)
        -   [Get Disbursement Available Banks](#get-disbursement-available-banks)
    -   [E-Wallets](#e-wallets)
        -   [Create E-Wallet Charge](#create-e-wallet-charge)
        -   [Get E-Wallet Charge Status](#get-e-wallet-charge-status)
    -   [Invoice](#invoice)
        -   [Create Invoice](#create-invoice)
        -   [Get Invoice](#get-invoice)
        -   [Get All Invoice](#get-all-invoice)
        -   [Expire Invoice](#expire-invoice)
    -   [Payouts](#payouts)
        -   [Create a Payout](#create-payout)
        -   [Get a Payout](#get-payout)
        -   [Void a Payout](#void-payout)
    -   [QR Code](#qr-code)
        -   [Create a QR Code](#create-a-qr-code)
        -   [Get QR Code](#get-qr-code)
    -   [Recurring](#recurring-payments)
        -   [Create a Recurring Payment](#create-a-recurring-payment)
        -   [Get a Recurring Payment](#get-a-recurring-payment)
        -   [Edit a Recurring Payment](#edit-recurring-payment)
        -   [Pause a Recurring Payment](#pause-recurring-payment)
        -   [Stop a Recurring Payment](#stop-recurring-payment)
        -   [Resume a Recurring Payment](#resume-recurring-payment)
    -   [Retail Outlets](#retail-outlets)
        -   [Create Fixed Payment Code](#create-fixed-payment-code)
        -   [Update Fixed Payment Code](#update-fixed-payment-code)
        -   [Get Fixed Payment Code](#get-fixed-payment-code)
    -   [Virtual Accounts](#virtual-accounts)
        -   [Create Fixed Virtual Account](#create-fixed-virtual-account)
        -   [Get Virtual Account Bank](#get-virtual-account-bank)
        -   [Get Fixed Virtual Account](#get-fixed-virtual-account)
        -   [Update Fixed Virtual Account](#update-fixed-virtual-account)
        -   [Get Fixed Virtual Account Payment](#get-fixed-virtual-account-payment)
-   [Exceptions](#exceptions)
    -   [InvalidArgumentException](#invalidargumentexception)
    -   [ApiException](#apiexception)
    -   [ApiValidationException](#apivalidationexception)
-   [Contributing](#contributing)
    -   [Test](#tests)
        -   [Running test suite](#running-test-suite)
        -   [Running examples](#running-examples)

---

## Documentation

For the API documentation, check [Xendit API Reference](https://xendit.github.io/apireference).

## Installation

Install xendit-php-clients with composer by following command:

```bash
composer require xendit/xendit-php
```

or add it manually in your `composer.json` file.

### Update from v1.4.0 to v2.0.0

To update xendit-php-clients with composer, use the following command:

```bash
composer update xendit/xendit-php
```

To migrate, see [MIGRATE.md](MIGRATE.md) for more information.

## Usage

Configure package with your account's secret key obtained from [Xendit Dashboard](https://dashboard.xendit.co/settings/developers#api-keys).

```php
Xendit::setApiKey('secretKey');
```

See [example codes](./examples) for more details.

### Use Custom HTTP Client

A custom HTTP Client that implements [HttpClientInterface](./src/HttpClientInterface.php) can be injected like so

```php
Xendit::setHttpClient($client);
```

Checkout [custom http client example](./examples/CustomHttpClient.php) for implementation reference.

## Methods' Signature and Examples

### Balance

#### Get Balance

```php
\Xendit\Balance::getBalance(string $account_type);
```

Usage example:

```php
$getBalance = \Xendit\Balance::getBalance('CASH');
var_dump($getBalance);
```

### Cards

#### Create Charge

```php
\Xendit\Cards::create(array $params);
```

Usage example:

```php
$params = [
    'token_id' => '5e2e8231d97c174c58bcf644',
    'external_id' => 'card_' . time(),
    'authentication_id' => '5e2e8658bae82e4d54d764c0',
    'amount' => 100000,
    'card_cvn' => '123',
    'capture' => false
];

$createCharge = \Xendit\Cards::create($params);
var_dump($createCharge);
```

#### Reverse Authorization

```php
\Xendit\Cards::reverseAuthorization(string $id, array $params);
```

Usage example:

```php
$id = 'charge-id';
$params = ['external_id' => 'ext-id'];

$reverseAuth = \Xendit\Cards::reverseAuthorization(
    $id,
    $params
);
var_dump($reverseAuth);
```

#### Capture Charge

```php
\Xendit\Cards::capture(string $id, array $params);
```

Usage example:

```php
$id = 'charge-id';
$params = ['amount' => 100000];

$captureCharge = \Xendit\Cards::capture($id, $params);
var_dump($captureCharge);
```

#### Get Charge

```php
\Xendit\Cards::retrieve(string $id);
```

Usage example:

```php
$id = 'charge-id';

$getCharge = \Xendit\Cards::retrieve($id);
var_dump($getCharge);
```

#### Create Refund

```php
\Xendit\Cards::createRefund(string $id, array $params);
```

Usage examples

Without idempotency key:

```php
$params = [
    'external_id' => 'ext-id',
    'amount' => 20000
];

$refund = \Xendit\Cards::createRefund($id, $params);
var_dump($refund);
```

With idempotency key:

```php
$params = [
    'external_id' => 'ext-id',
    'amount' => 20000,
    'X-IDEMPOTENCY-KEY' => 'unique-id'
];

$refund = \Xendit\Cards::createRefund($id, $params);
var_dump($refund);
```

#### Create Promotion

```php
\Xendit\Promotion::create(array $params);
```

usage examples:

```php
$params = [
    'reference_id' => 'reference_123',
    'description' => 'test promotion',
    'currency' => 'IDR',
    'start_time' => '2021-01-01T00:00:00.000Z',
    'end_time' => '2021-01-02T00:00:00.000Z',
    'promo_code' => 'testpromo',
    'discount_amount' => 5000
];

$createPromotion = \Xendit\Promotion::create($params);
var_dump($createPromotion);
```

### Cardless Credit

#### Create Cardless Credit Payment

```php
\Xendit\CardlessCredit::create(array $params);
```

Usage example:

```php
$params = [
    'cardless_credit_type' => 'KREDIVO',
    'external_id' => 'test-cardless-credit-02',
    'amount' => 800000,
    'payment_type' => '3_months',
    'items' => [
        [
            'id' => '123123',
            'name' => 'Phone Case',
            'price' => 200000,
            'type' => 'Smartphone',
            'url' => 'http=>//example.com/phone/phone_case',
            'quantity' => 2
        ],
        [
            'id' => '234567',
            'name' => 'Bluetooth Headset',
            'price' => 400000,
            'type' => 'Audio',
            'url' => 'http=>//example.com/phone/bluetooth_headset',
            'quantity' => 1
        ]
    ],
    'customer_details' => [
        'first_name' => 'customer first name',
        'last_name' => 'customer last name',
        'email' => 'customer@yourwebsite.com',
        'phone' => '081513114262'
    ],
    'shipping_address' => [
        'first_name' => 'first name',
        'last_name' => 'last name',
        'address' => 'Jalan Teknologi No. 12',
        'city' => 'Jakarta',
        'postal_code' => '12345',
        'phone' => '081513114262',
        'country_code' => 'IDN'
    ],
    'redirect_url' => 'https://example.com',
    'callback_url' => 'http://example.com/callback-cardless-credit'
];

$createPayment = \Xendit\CardlessCredit::create($params);
var_dump($createPayment);
```

#### Calculate Payment Types

```php
\Xendit\CardlessCredit::calculatePaymentTypes(array $params);
```

Usage example:

```php
$params = [
    'cardless_credit_type' => 'KREDIVO',
    'amount' => 2000000,
    'items' => [
        [
            'id' => '123123',
            'name' => 'Phone Case',
            'price' => 1000000,
            'type' => 'Smartphone',
            'url' => 'http://example.com/phone/phone_case',
            'quantity' => 2
        ]
    ]
];

$calculatePaymentTypes = \Xendit\CardlessCredit::calculatePaymentTypes($params);
var_dump($calculatePaymentTypes);
```

### Disbursements

#### Create Disbursement

```php
\Xendit\Disbursements::create(array $params);
```

Usage examples

Without idempotency key:

```php
$params = [
    'external_id' => 'disb-12345678',
    'amount' => 15000,
    'bank_code' => 'BCA',
    'account_holder_name' => 'Joe',
    'account_number' => '1234567890',
    'description' => 'Disbursement from Example'
];

$createDisbursements = \Xendit\Disbursements::create($params);
var_dump($createDisbursements);
```

With idempotency key:

```php
$params = [
    'external_id' => 'disb-12345678',
    'amount' => 15000,
    'bank_code' => 'BCA',
    'account_holder_name' => 'Joe',
    'account_number' => '1234567890',
    'description' => 'Disbursement from Example',
    'X-IDEMPOTENCY-KEY' => 'unique-id'
];

$createDisbursements = \Xendit\Disbursements::create($params);
var_dump($createDisbursements);
```

#### Create Batch Disbursement

```php
\Xendit\Disbursements::createBatch(array $params);
```

Usage example:

```php
$batch_params = [
    'reference' => 'disb_batch-12345678',
    'disbursements' => [
        [
            'amount' => 20000,
            'bank_code' => 'BCA',
            'bank_account_name' => 'Fadlan',
            'bank_account_number' => '1234567890',
            'description' => 'Batch Disbursement',
            'external_id' => 'disbursement-1'
        ],
        [
            'amount' => 30000,
            'bank_code' => 'MANDIRI',
            'bank_account_name' => 'Lutfi',
            'bank_account_number' => '1234567891',
            'description' => 'Batch Disbursement with email notifications',
            'external_id' => 'disbursement-2',
            'email_to' => ['test+to@xendit.co'],
            'email_cc' => ['test+cc@xendit.co'],
            'email_bcc' => ['test+bcc1@xendit.co', 'test+bcc2@xendit.co']
        ]
    ]
];

$createBatchDisbursements = \Xendit\Disbursements::createBatch($batch_params);
var_dump($createBatchDisbursements);
```

#### Get Disbursement by ID

```php
\Xendit\Disbursements::retrieve(string $id);
```

Usage example:

```php
$id = 'disbursements-id';
$getDisbursements = \Xendit\Disbursements::retrieve($id);
var_dump($getDisbursements);
```

#### Get Disbursement by External ID

```php
\Xendit\Disbursements::retrieveExternal(string $external_id);
```

Usage example:

```php
$external_id = 'disbursements-ext-id';
$getDisbursementsByExt = \Xendit\Disbursements::retrieveExternal($external_id);
var_dump($getDisbursementsByExt);
```

#### Get Disbursement Available Banks

```php
\Xendit\Disbursements::getAvailableBanks();
```

Usage example:

```php
$getDisbursementsBanks = \Xendit\Disbursements::getAvailableBanks();
var_dump($getDisbursementsBanks);
```

### E-Wallets

#### Create E-Wallet Charge

```php
\Xendit\EWallets::createEWalletCharge(array $params);
```

For more information about the params, please check [Xendit API Reference - E-Wallets](https://developers.xendit.co/api-reference/#create-ewallet-charge).

Usage example:

```php
$ewalletChargeParams = [
    'reference_id' => 'test-reference-id',
    'currency' => 'IDR',
    'amount' => 50000,
    'checkout_method' => 'ONE_TIME_PAYMENT',
    'channel_code' => 'ID_SHOPEEPAY',
    'channel_properties' => [
        'success_redirect_url' => 'https://yourwebsite.com/order/123',
    ],
    'metadata' => [
        'meta' => 'data'
    ]
];

$createEWalletCharge = \Xendit\EWallets::createEWalletCharge($ewalletChargeParams);
var_dump($createEWalletCharge);
```

#### Get E-Wallet Charge Status

```php
\Xendit\EWallets::getEWalletChargeStatus(string $charge_id);
```

Usage example:

```php
$charge_id = 'ewc_f3925450-5c54-4777-98c1-fcf22b0d1e1c';
$getEWalletChargeStatus = \Xendit\EWallets::getEWalletChargeStatus($charge_id);
var_dump($getEWalletChargeStatus);
```

### Invoice

#### Create Invoice

```php
\Xendit\Invoice::create(array $params);
```

Usage example:

```php
$params = ['external_id' => 'demo_147580196270',
    'payer_email' => 'sample_email@xendit.co',
    'description' => 'Trip to Bali',
    'amount' => 32000
];

$createInvoice = \Xendit\Invoice::create($params);
var_dump($createInvoice);
```

#### Get Invoice

```php
\Xendit\Invoice::retrieve(string $id);
```

Usage example:

```php
$id = 'invoice-id';
$getInvoice = \Xendit\Invoice::retrieve($id);
var_dump($getInvoice);
```

#### Get All Invoice

```php
\Xendit\Invoice::retrieveAll();
```

Usage example:

```php
$getAllInvoice = \Xendit\Invoice::retrieveAll();
var_dump(($getAllInvoice));
```

#### Expire Invoice

```php
\Xendit\Invoice::expireInvoice(string $id);
```

Usage example:

```php
$id = 'invoice-id';
$expireInvoice = \Xendit\Invoice::expireInvoice($id);
var_dump($expireInvoice);
```

### Payouts

#### Create Payout

```php
\Xendit\Payouts::create(array $params);
```

Usage example:

```php
$params = [
    'external_id' => 'payouts-123456789',
    'amount' => 50000
];

$createPayout = \Xendit\Payouts::create($params);
var_dump($createPayout);
```

#### Get Payout

```php
\Xendit\Payouts::retrieve(string $id);
```

Usage example:

```php
$id = 'payout-id';

$getPayout = \Xendit\Payouts::retrieve($id);
var_dump($getPayout);
```

#### Void Payout

```php
\Xendit\Payouts::void(string $id);
```

Usage example:

```php
$id = 'payout-id';

$voidPayout = \Xendit\Payouts::void($id);
var_dump($voidPayout);
```

### QR Code

#### Create a QR Code

```php
\Xendit\QRCode::create(array $params);
```

Usage example:

```php
$params = [
    'external_id' => 'demo_123456',
    'type' => 'STATIC',
    'callback_url' => 'https://webhook.site',
    'amount' => 10000,
];

$qr_code = \Xendit\QRCode::create($params);
var_dump($qr_code)
```

#### Get QR Code

```php
\Xendit\QRCode::get(string $external_id);
```

Usage example:

```php
$qr_code = \Xendit\QRCode::get('external_123');
var_dump($qr_code);
```

### Recurring Payments

#### Create a Recurring Payment

```php
\Xendit\Recurring::create(array $params);
```

Usage example:

```php
$params = [
    'external_id' => 'demo_147580196270',
    'payer_email' => 'sample_email@xendit.co',
    'description' => 'Trip to Bali',
    'amount' => 32000,
    'interval' => 'MONTH',
    'interval_count' => 1
];

$createRecurring = \Xendit\Recurring::create($params);
var_dump($createRecurring);
```

#### Get a Recurring Payment

```php
\Xendit\Recurring::retrieve(string $id);
```

Usage example:

```php
$id = 'recurring-payment-id';

$getRecurring = \Xendit\Recurring::retrieve($id);
var_dump($getRecurring);
```

#### Edit Recurring Payment

```php
\Xendit\Recurring::update(string $id, array $params);
```

Usage example:

```php
$id = 'recurring-payment-id';
$params = ['amount' => 10000];

$editRecurring = \Xendit\Recurring::update($id, $params);
var_dump($editRecurring);
```

#### Stop Recurring Payment

```php
\Xendit\Recurring::stop(string $id);
```

Usage example:

```php
$id = 'recurring-payment-id';

$stopRecurring = \Xendit\Recurring::stop($id);
var_dump($stopRecurring);
```

#### Pause Recurring Payment

```php
\Xendit\Recurring::pause(string $id);
```

Usage example:

```php
$id = 'recurring-payment-id';

$pauseRecurring = \Xendit\Recurring::pause($id);
var_dump($pauseRecurring);
```

#### Resume Recurring Payment

```php
\Xendit\Recurring::resume(string $id);
```

Usage example:

```php
$id = 'recurring-payment-id';

$resumeRecurring = \Xendit\Recurring::resume($id);
var_dump($resumeRecurring);
```

### Retail Outlets

#### Create Fixed Payment Code

```php
\Xendit\Retail::create(array $params);
```

Usage example:

```php
$params = [
    'external_id' => 'TEST-123456789',
    'retail_outlet_name' => 'ALFAMART',
    'name' => 'JOHN DOE',
    'expected_amount' => 25000
];

$createFPC = \Xendit\Retail::create($params);
var_dump($createFPC);
```

#### Update Fixed Payment Code

```php
\Xendit\Retail::update(string $id, array $params);
```

Usage example:

```php
$id = 'FPC-id';
$updateParams = ['expected_amount' => 20000];

$updateFPC = \Xendit\Retail::update($id, $updateParams);
var_dump($updateFPC);
```

#### Get Fixed Payment Code

```php
\Xendit\Retail::retrieve(string $id);
```

Usage example:

```php
$id = 'FPC-id';
$getFPC = \Xendit\Retail::retrieve($id);
var_dump($getFPC);
```

### Virtual Accounts

#### Create Fixed Virtual Account

```php
\Xendit\VirtualAccounts::create(array $params);
```

Usage example:

```php
$params = ["external_id" => "VA_fixed-12341234",
   "bank_code" => "MANDIRI",
   "name" => "Steve Wozniak"
];

$createVA = \Xendit\VirtualAccounts::create($params);
var_dump($createVA);
```

#### Get Virtual Account Bank

```php
\Xendit\VirtualAccounts::getVABanks();
```

Usage example:

```php
$getVABanks = \Xendit\VirtualAccounts::getVABanks();
var_dump($getVABanks);
```

#### Get Fixed Virtual Account

```php
\Xendit\VirtualAccounts::retrieve(string $id);
```

Usage example:

```php
$id = 'VA-id';
$getVA = \Xendit\VirtualAccounts::retrieve($id);
var_dump($getVA);
```

#### Update Fixed Virtual Account

```php
\Xendit\VirtualAccounts::update(string $id, array $params);
```

Usage example:

```php
$id = 'VA-id';
$updateParams = ["suggested_amount" => 1000];

$updateVA = \Xendit\VirtualAccounts::update($id, $updateParams);
var_dump($updateVA);
```

#### Get Fixed Virtual Account Payment

```php
\Xendit\VirtualAccounts::getFVAPayment(string $paymentID);
```

Usage example:

```php
$paymentID = 'payment-ID';
$getFVAPayment = \Xendit\VirtualAccounts::getFVAPayment($paymentID);
var_dump($getFVAPayment);
```

## Exceptions

### InvalidArgumentException

`InvalidArgumentException` will be thrown if the argument provided by user is not sufficient to create the request.

For example, there are required arguments such as `external_id`, `payer_email`, `description`, and `amount` to create an invoice. If user lacks one or more arguments when attempting to create one, `InvalidArgumentException` will be thrown.

`InvalidArgumentException` is derived from PHP's `InvalidArgumentException`. For more information about this Exception methods and properties, please check [PHP Documentation](https://www.php.net/manual/en/class.invalidargumentexception.php).

### ApiException

`ApiException` wraps up Xendit API error. This exception will be thrown if there are errors from Xendit API side, e.g. get fixed virtual account with invalid `id`.

To get exception message:

```php
try {
    $getInvoice = \Xendit\Invoice::retrieve('123');
} catch (\Xendit\Exceptions\ApiException $e) {
    var_dump($e->getMessage());
}
```

To get exception HTTP error code:

```php
try {
    $getInvoice = \Xendit\Invoice::retrieve('123');
} catch (\Xendit\Exceptions\ApiException $e) {
    var_dump($e->getCode());
}
```

To get exception Xendit API error code:

```php
try {
    $getInvoice = \Xendit\Invoice::retrieve('123');
} catch (\Xendit\Exceptions\ApiException $e) {
    var_dump($e->getErrorCode());
}
```

### ApiValidationException

`ApiValidationException` is the subset of [`ApiException`](#apiexception) that will be thrown in case of invalid parameters in the requests. Compared to `ApiException`, `ApiValidationException` has additional information of the list of invalid parameters and the reason why they are invalid.

To get the list of invalid parameters:

```php
try {
    $getInvoice = \Xendit\EWallets::create($params);
} catch (\Xendit\Exceptions\ApiValidationException $e) {
    foreach ($e->getValidationErrors() as $validationError) {
        var_dump($validationError->getPath()); // the location of the invalid parameter in the request
        var_dump($validationError->getMessage()); // the reason why the parameter is invalid
    }
}
```

## Contributing

For any requests, bugs, or comments, please open an [issue](https://github.com/xendit/xendit-php-clients/issues) or [submit a pull request](https://github.com/xendit/xendit-php-clients/pulls).

### Installing Packages

Before you start to code, run this command to install all of the required packages. Make sure you have `composer` installed in your computer

```bash
composer install
```

### Tests

#### Running test suite:

```bash
vendor\bin\phpunit tests
```

#### Running examples:

```bash
php examples\InvoiceExample.php
```

There is a pre-commit hook to run phpcs and phpcbf. Please make sure they passed before making commits/pushes.
