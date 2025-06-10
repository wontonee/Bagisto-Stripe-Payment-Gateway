# Bagisto Stripe Payment Gateway
Stripe is a globally trusted payment gateway. This package provides comprehensive support for **onsite payment processing** - customers complete their payments directly on your website without being redirected to external pages, ensuring a seamless shopping experience.

---

## Licensing Information

You must obtain a license from [https://myapps.wontonee.com](https://myapps.wontonee.com), either trial or paid.

- **Trial License**: Works for 7 days - perfect for testing
- **Paid License**: Valid for 1 year, costs only ₹800  
  Includes updates, support, and all premium features

## Video Tutorial

[![Bagisto Stripe Payment Gateway Tutorial](https://img.youtube.com/vi/8GnASKq4-PI/maxresdefault.jpg)](https://www.youtube.com/watch?v=8GnASKq4-PI)

## 🎯 Key Benefits

### For Store Owners
- ✅ **Onsite Payment Processing**: No external redirects - customers stay on your website
- ✅ **Global Payment Support**: Accept cards from customers worldwide
- ✅ **Complete Brand Control**: Custom styling and logos for professional appearance
- ✅ **Easy Configuration**: User-friendly admin interface with clear options

### For Customers
- ✅ **Seamless Experience**: Pay without leaving your website
- ✅ **Secure Processing**: Industry-leading security with SSL encryption
- ✅ **Multiple Payment Methods**: Credit cards, debit cards, and digital wallets
- ✅ **Fast Checkout**: Optimized for quick, hassle-free payments
- ✅ **Mobile Optimized**: Perfect experience across all devices

## ✨ What's New in Latest Version

### 🎨 Enhanced Branding & Customization
- **Professional Payment Forms**: Beautiful, responsive payment interfaces
- **Custom Logo Support**: Display your brand logo in payment forms
- **Modern UI Design**: Clean, professional styling that builds customer trust

### 🔧 Advanced Payment Features
- **Onsite Processing**: Complete payments without external redirects
- **Real-time Validation**: Instant card validation and error handling
- **Multiple Card Types**: Support for Visa, Mastercard, Amex, and more

### 🛡️ Security & Performance
- **Enhanced CSRF Protection**: Secure form submission handling
- **Fraud Detection**: Stripe's advanced fraud prevention included
- **Optimized Performance**: Fast loading and processing times

### 📱 User Experience Enhancements
- **Mobile-First Design**: Optimized for mobile and tablet users
- **Error Handling**: Clear, helpful error messages for users
- **Loading Indicators**: Professional payment processing feedback
- **Accessibility**: WCAG compliant for all users

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

## 🚀 Features

### Onsite Payment Processing
- **No External Redirects**: Customers complete payments directly on your website
- **Seamless Integration**: Payment forms blend perfectly with your checkout process
- **Real-time Processing**: Instant payment validation and confirmation
- **Secure Handling**: All sensitive data processed securely through Stripe's servers

### Payment Gateway Customization
- **Mobile Responsive**: Optimized design for all device types

### Advanced Security Features
- **Fraud Protection**: Stripe's advanced fraud detection included
- **Secure Tokenization**: Card details never stored on your servers

### Developer-Friendly
- **Easy Configuration**: Simple admin panel setup
- **Comprehensive Documentation**: Clear setup and troubleshooting guides
- **Regular Updates**: Ongoing compatibility and security updates
- **Priority Support**: Dedicated support for licensed users

## Configuration

After installation, navigate to **Admin Panel → Configuration → Sales → Payment Methods → Stripe** to configure:

1. **License Key**: Enter your Wontonee license key
2. **Stripe API Keys**: Add your Stripe Publishable and Secret keys
3. **Payment Method Logo**: Upload your brand logo (optional)
4. **Test/Live Mode**: Configure for testing or production
5. **Activate**: Enable the payment method

## 💬 Special Discount Offer

🎉 **Get Exclusive Discounts!** Contact us on WhatsApp for special pricing:

**WhatsApp**: [+91 9711381236](https://wa.me/919711381236)

- Bulk license discounts available
- Custom development services
- Priority support options
- Extended license terms
- Multi-site licensing deals


## Troubleshooting

1. **Payment Form Not Loading**: Clear configuration cache using `php artisan config:cache`

2. **Route Error After Payment**: Navigate to `bootstrap/cache` and delete all cache files, then run `php artisan route:clear`

3. **Logo Not Displaying**: Ensure images are uploaded in supported formats (bmp, jpeg, jpg, png, webp)

4. **Test Payments Failing**: Verify your Stripe test API keys are correctly configured

5. **Live Mode Issues**: Ensure your Stripe account is fully activated and live keys are entered

## 🎯 Why Choose Our Stripe Integration?

### Compared to Basic Stripe Integrations:
- ✅ **No Redirects**: Keep customers on your site throughout the payment process
- ✅ **Professional Design**: Beautiful, trust-inspiring payment forms
- ✅ **Brand Consistency**: Custom logos and colors maintain your brand identity
- ✅ **Enhanced Security**: Additional security layers beyond basic Stripe integration
- ✅ **Priority Support**: Direct support from our development team
- ✅ **Regular Updates**: Ongoing compatibility with latest Bagisto versions

### Perfect For:
- 🛍️ **E-commerce Stores**: Seamless checkout experience for online shops
- 💼 **Business Services**: Professional payment processing for service providers
- 🌍 **Global Businesses**: Accept payments from customers worldwide
- 📱 **Mobile Commerce**: Optimized for mobile shopping experiences

## Support & Contact

For any help, customization, or questions:
- 🌐 **Website**: [https://www.wontonee.com](https://www.wontonee.com)
- 📧 **Email**: [saju@wontonee.com](mailto:saju@wontonee.com)
- 💬 **WhatsApp**: [+91 9711381236](https://wa.me/919711381236)
- 🎥 **Video Tutorial**: [Watch Setup Guide](https://www.youtube.com/watch?v=8GnASKq4-PI)

### 🚀 Professional Services Available:
- Custom payment form design
- Multi-gateway integration
- Advanced fraud protection setup
- Performance optimization

---

**Made with ❤️ by [Wontonee](https://www.wontonee.com) - Your Trusted Payment Gateway Partner**
