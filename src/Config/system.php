<?php

return [
    [
        'key'    => 'sales.payment_methods.stripe',
        'info'   => 'Professional Stripe Payment Gateway for Bagisto. <div style="margin-top: 10px; padding: 15px; background: #f8f9ff; border: 1px solid #e3f2fd; border-radius: 8px;"><div style="display: flex; align-items: center; margin-bottom: 8px;"><span style="color: #1976d2; font-weight: 600; font-size: 14px;">🚀 Get Your License Key</span></div><p style="margin: 0 0 10px 0; color: #424242; font-size: 13px; line-height: 1.4;">Unlock the full power of Stripe payments with advanced features, priority support, and regular updates.</p><a href="https://myapps.wontonee.com/" target="_blank" style="display: inline-flex; align-items: center; background: #1976d2; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 13px; transition: all 0.2s;"><i class="fas fa-key" style="margin-right: 6px;"></i>Get License Now<i class="fas fa-external-link-alt" style="margin-left: 6px; font-size: 11px;"></i></a><div style="margin-top: 10px; font-size: 12px; color: #666;"><span style="color: #4caf50;">✓</span> 7-day free trial available</div><div style="margin-top: 8px; padding: 8px; background: #fff3e0; border: 1px solid #ffcc02; border-radius: 4px; font-size: 12px;"><span style="color: #f57c00; font-weight: 600;">💬 Special Discount!</span> <span style="color: #424242;">Contact us on WhatsApp <a href="https://wa.me/919711381236" target="_blank" style="color: #25d366; font-weight: 600; text-decoration: none;">+91 9711381236</a> to avail exclusive discount offers!</span></div></div>',
        'name'   => 'Stripe',
        'sort'   => 7,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'Stripe',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'Stripe Gateway',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ],            [
                'name'          => 'image',
                'title'         => 'Logo',
                'type'          => 'image',
                'channel_based' => false,
                'locale_based'  => false,
                'validation'    => 'mimes:bmp,jpeg,jpg,png,webp',
            ],
             [
                'name'          => 'stripe_api_publishable_key',
                'title'         => 'Strip Publishable Key',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ],            [
                'name'          => 'stripe_api_key',
                'title'         => 'Strip Secret Key',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ],
            [
                'name'          => 'license_key',
                'title'         => 'License Key',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => false,
                'info'          => 'Enter your valid license key from wontonee.com',
            ],
            [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.payment-methods.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ]
        ]
    ]
];
