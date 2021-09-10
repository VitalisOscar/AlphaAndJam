<?php

return [
    /*
     * Pesapal consumer key
     */
    'consumer_key'    => 'fnagRv9mSk7Eo19Fo09P+z9XhDjZks06',

    /*
     * Pesapal consumer secret
     */
    'consumer_secret' => 'Rb64fJ/ORux0EFXtbs7RZUfAZFo=',

    /*
     * ISO code for the currency
     */
    'currency'        => env('PESAPAL_CURRENCY', 'KES'),

    /*
     * controller method to call for instant notifications IPN as relative path from App\Http\Controllers\
     * eg "TransactionController@confirmation"
     */
    'ipn'             => config('app.url').'/pesapal/ipn',

    /*
     * Pesapal environment
     */
    'live'            => env('PESAPAL_LIVE', false),

    /*
     * Route name to handle the callback
     * eg Route::get('donepayment', ['as' => 'paymentsuccess', 'uses'=>'PaymentsController@paymentsuccess']);
     * The route name is "paymentsuccess"
     */
    'callback_route'  => env('PESAPAL_CALLBACK_ROUTE'),

];
