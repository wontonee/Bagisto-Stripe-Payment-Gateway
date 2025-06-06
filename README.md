# Bagisto Stripe Payment Gateway
Stripe is a popular payment gateway. This package provides strong support for users to integrate the Stripe payment gateway into their Bagisto Laravel e-commerce applications.

## Video Tutorial
![DD1EA53C-FAA8-4884-A30C-977024113AB1_1_102_o](https://github.com/user-attachments/assets/9093866c-9574-43bb-95b4-02f96ba774b3)



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

4. Navigate to the `admin panel -> Configure/Payment Methods`, where Stripe will be visible at the end of the payment method list.

5. **Configure License**: In the Stripe payment method settings, enter your license key obtained from step 1.

6. Now run the following commands to optimize your application:
```sh
php artisan config:cache
php artisan optimize
```



## Troubleshooting

1. If you encounter an issue where you are not redirected to the payment gateway after placing an order and receive a route error, navigate to `bootstrap/cache` and delete all cache files.


For any help or customization, visit <https://www.wontonee.com> or email us <dev@wontonee.com>
