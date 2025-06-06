<?php

namespace Wontonee\Stripe\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Wontonee\Stripe\Helpers\LicenseHelper;

class ValidateStripeLicense
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $action
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $action = 'general')
    {
        $validation = LicenseHelper::validateStripeAction($action);
        
        if (!$validation['success']) {
            // For AJAX requests, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'License validation failed: ' . $validation['message'],
                    'license_error' => true
                ], 403);
            }
            
            // For regular requests, redirect with error
            return redirect()->route('shop.checkout.cart.index')
                ->with('error', 'Stripe Payment: ' . $validation['message']);
        }
        
        return $next($request);
    }
}
