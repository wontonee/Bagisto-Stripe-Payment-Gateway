<?php

namespace Wontonee\Stripe\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Psr\Log\LogLevel;

class LicenseHelper
{
    /**
     * Get Stripe-specific logger
     */    
    private static function getStripeLogger()
    {
        $logger = new Logger('stripe');
        $handler = new RotatingFileHandler(
            storage_path('logs/stripe.log'),
            14, // Keep 14 days of logs
            LogLevel::DEBUG
        );
        $logger->pushHandler($handler);
        return $logger;
    }

    /**
     * Validate license for specific action
     */
    public static function validateStripeAction($action)
    {
        try {
            // Get license key from configuration
            $licenseKey = core()->getConfigData('sales.payment_methods.stripe.license_key');

            if (empty($licenseKey)) {
                return [
                    'success' => false,
                    'message' => 'License key not configured. Please enter your license key in Stripe settings.'
                ];
            }            // Get configuration from package config
            $apiUrl = config('stripe.license.api_url');
            $productId = config('stripe.license.product_id');
            $timeout = config('stripe.license.api_timeout', 10);
            $debug = config('stripe.license.debug', false);

            // Validate required configuration
            if (empty($apiUrl)) {
                return [
                    'success' => false,
                    'message' => 'License API URL not configured. Please check your configuration.'
                ];
            }

            if (empty($productId)) {
                return [
                    'success' => false,
                    'message' => 'Product ID not configured. Please check your configuration.'
                ];
            }

            // Prepare API request data
            $requestData = [
                'license_key' => $licenseKey,
                'product_id' => $productId,
                'action' => $action,
                'domain' => request()->getHost()
            ];
            if ($debug) {
                self::getStripeLogger()->info('Stripe License Validation Request', [
                    'api_url' => $apiUrl,
                    'license_key' => substr($licenseKey, 0, 8) . '...',
                    'product_id' => $productId,
                    'action' => $action,
                    'domain' => $requestData['domain']
                ]);
            }

            //   dd( $requestData);

            // Make HTTP POST request to validate license
            $response = Http::timeout($timeout)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($apiUrl, $requestData);

            // Handle response
            if ($response->successful()) {
                $data = $response->json();
                if ($debug) {
                    self::getStripeLogger()->info('Stripe License API Response', [
                        'status_code' => $response->status(),
                        'response_data' => $data
                    ]);
                } // Check if license is authorized
                if (isset($data['status']) && $data['status'] === 'authorized') {
                    return [
                        'success' => true,
                        'message' => 'License validated successfully'
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => $data['message'] ?? $data['error'] ?? 'License not authorized'
                    ];
                }
            } else {                // Log API error if debugging is enabled
                if ($debug) {
                    self::getStripeLogger()->error('Stripe License API Error', [
                        'status_code' => $response->status(),
                        'response_body' => $response->body(),
                        'license_key' => substr($licenseKey, 0, 8) . '...',
                        'api_url' => $apiUrl
                    ]);
                }

                return [
                    'success' => false,
                    'message' => 'License validation server returned error: ' . $response->status()
                ];
            }
        } catch (\Exception $e) {            // Log error if debugging is enabled
            if ($debug) {
                self::getStripeLogger()->error('Stripe License Validation Error', [
                    'error' => $e->getMessage(),
                    'license_key' => substr($licenseKey, 0, 8) . '...',
                    'api_url' => $apiUrl
                ]);
            }

            return [
                'success' => false,
                'message' => 'License validation failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validate license for showing payment form
     */
    public static function validateShowForm()
    {
        return self::validateStripeAction('show_form');
    }

    /**
     * Validate license for creating payment intent
     */
    public static function validateCreateIntent()
    {
        return self::validateStripeAction('create_intent');
    }

    /**
     * Validate license for processing payment
     */
    public static function validateProcessPayment()
    {
        return self::validateStripeAction('process_payment');
    }
}
