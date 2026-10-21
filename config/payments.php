<?php

use App\Payments\Gateways\CreditCardGateway;
use App\Payments\Gateways\PaypalGateway;

return [

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    */

    'currency' => env('PAYMENT_CURRENCY', 'USD'),

    /*
    |--------------------------------------------------------------------------
    | Payment Gateways
    |--------------------------------------------------------------------------
    |
    | The key is the payment_method clients send. To add a gateway: create a
    | class implementing PaymentGatewayInterface, add an entry here, and put
    | its credentials in .env. Nothing else needs to change.
    |
    */

    'gateways' => [

        'credit_card' => [
            'class' => CreditCardGateway::class,
            'api_key' => env('CREDIT_CARD_API_KEY'),
            'secret' => env('CREDIT_CARD_SECRET'),
            'decline_suffix' => env('CREDIT_CARD_DECLINE_SUFFIX', '0002'),
        ],

        'paypal' => [
            'class' => PaypalGateway::class,
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'secret' => env('PAYPAL_SECRET'),
            'decline_domain' => env('PAYPAL_DECLINE_DOMAIN', 'decline.test'),
        ],

    ],

];
