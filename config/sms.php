<?php

return [
    /**
     * SMS Sending carrier
     * 'at' for Africa's Talking
     */
    'carrier' => env('SMS_CARRIER', 'at'),

    /**
     * Fake SMS sending, if true, messages will not go through carrier
     */
    'fake' => env('FAKE_SMS', false),

    /**
     * SMS Carriers
     */
    'at_username' => env('AFRICAS_TALKING_USERNAME', 'sandbox'),
    'at_api_key' => env('AFRICAS_TALKING_API_KEY', 'sandbox'),
];
