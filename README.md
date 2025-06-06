# Bagisto Stripe Payment Gateway
Stripe is a popular payment gateway. This package provides strong support for users to integrate the Stripe payment gateway into their Bagisto Laravel e-commerce applications.

**<span style="color:red;">Support Bagisto v2.2. For Bagisto 2.1, you can downgrade the package to 2.0.1</span>**

## Installation
1. **Get a License**: Visit [https://myapps.wontonee.com](https://myapps.wontonee.com) to obtain your Stripe payment gateway license. Trial licenses work for 7 days only.

2. Use the command prompt to install this package:
```sh
composer require wontonee/stripe
```

3. Publish the package assets:
```sh
php artisan vendor:publish --tag=stripe-assets
```

4. Open `config/app.php` and register the Stripe provider.
```sh
'providers' => [
        // Stripe provider
        Wontonee\Stripe\Providers\StripeServiceProvider::class,
]
```

5. Navigate to the `admin panel -> Configure/Payment Methods`, where Stripe will be visible at the end of the payment method list.

6. **Configure License**: In the Stripe payment method settings, enter your license key obtained from step 1.

7. Now open `app\Http\Middleware\VerifyCsrfToken.php` and add this route to the exception list.
```sh
protected $except = [
    '/stripe-success',
    '/stripe-cancel'
];
```

8. Now run `php artisan config:cache`



## Troubleshooting

1. If you encounter an issue where you are not redirected to the payment gateway after placing an order and receive a route error, navigate to `bootstrap/cache` and delete all cache files.


For any help or customization, visit <https://www.wontonee.com> or email us <dev@wontonee.com>
