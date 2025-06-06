<?php

return [
    /*
    |--------------------------------------------------------------------------
    | License Validation Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your license validation API settings here.
    |
    */
    
    // License validation API endpoint
   // 'api_url' => env('STRIPE_LICENSE_API_URL', 'http://licenseapp.test/api/process-stripe-data'),
    'api_url' => env('STRIPE_LICENSE_API_URL', 'https://myapps.wontonee.com/api/process-stripe-data'),
    
    
    // API request timeout in seconds
    'api_timeout' => 10,
    
    // Product ID for Stripe extension
    'product_id' => 'StripeBagisto',
    
    // Enable debug logging (set to false in production)
    'debug' => env('APP_DEBUG', false),
];
