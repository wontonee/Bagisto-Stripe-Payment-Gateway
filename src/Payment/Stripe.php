<?php

namespace Wontonee\Stripe\Payment;

use Webkul\Payment\Payment\Payment;
use Illuminate\Support\Facades\Storage;

class Stripe extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'stripe';

    public function getRedirectUrl()
    {
        return route('stripe.process');
    }    /**
     * Get payment method image.
     *
     * @return array
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        if ($url) {
            return Storage::url($url);
        }

        // Fallback to default Stripe logo
        return asset('vendor/wontonee/stripe/logo/stripe_logo.png');
    }
}
