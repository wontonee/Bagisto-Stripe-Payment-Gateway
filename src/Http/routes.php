<?php

use Illuminate\Support\Facades\Route;
use Wontonee\Stripe\Http\Controllers\StripeController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency']], function () {
    Route::get('stripe-redirect', [StripeController::class, 'redirect'])->name('stripe.process');
    Route::get('stripe-success', [StripeController::class, 'success'])->name('stripe.success');
    Route::get('stripe-cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
    
    // Payment card view with license validation
    Route::get('stripe-payment-card', [StripeController::class, 'showPaymentCard'])
        ->middleware(\Wontonee\Stripe\Http\Middleware\ValidateStripeLicense::class . ':show_form')
        ->name('stripe.payment.card');
        
    Route::post('stripe-process-payment', [StripeController::class, 'processPayment'])
        ->middleware(\Wontonee\Stripe\Http\Middleware\ValidateStripeLicense::class . ':process_payment')
        ->name('stripe.process.payment');
});

// Stripe Payment Intent creation with license validation
Route::post('stripe-create-payment-intent', [StripeController::class, 'createPaymentIntent'])
    ->middleware(['web', 'locale', 'currency', \Wontonee\Stripe\Http\Middleware\ValidateStripeLicense::class . ':create_intent'])
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)
    ->name('stripe.create.payment.intent');
