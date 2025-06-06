<x-shop::layouts>    <x-slot:title>
        Secure Payment - Stripe Checkout
    </x-slot>
    
    <!-- CSRF Token Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
          @pushOnce('styles')
        <style>
            /* Override any existing CSS that might interfere */
            .payment-grid {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: 1.5rem !important;
                width: 100% !important;
                min-height: 100vh !important;
                align-items: start !important;
                box-sizing: border-box !important;
            }

            @media (min-width: 768px) {
                .payment-grid {
                    grid-template-columns: 2fr 1fr !important;
                    gap: 2rem !important;
                    align-items: start !important;
                }

                .payment-form-column {
                    min-width: 0 !important;
                    width: 100% !important;
                    box-sizing: border-box !important;
                }

                .payment-summary-column {
                    min-width: 0 !important;
                    width: 100% !important;
                    position: sticky !important;
                    top: 2rem !important;
                    align-self: start !important;
                    box-sizing: border-box !important;
                }
            }

            /* Prevent layout shifts and override framework styles */
            .payment-grid>* {
                min-width: 0 !important;
                overflow-wrap: break-word !important;
                box-sizing: border-box !important;
            }

            /* Ensure grid container doesn't get overridden */
            .payment-grid[class] {
                display: grid !important;
            }
        </style>
        @endPushOnce
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
            <div class="container mx-auto px-4">
                <div class="max-w-7xl mx-auto">
                    <!-- Force two column layout -->
                    <div class="payment-grid">
                        <!-- Payment Form - Takes 2/3 of the space (LEFT COLUMN) -->
                        <div class="payment-form-column order-1">
                            <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8" style="width: 100%; min-width: 0;">
                                <!-- Header -->
                                <div class="mb-8">
                                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment Information</h1>
                                    <p class="text-gray-600 mb-4">Complete your purchase securely with Stripe</p>

                                    <!-- Error Message Display -->
                                    @if(session('error'))
                                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                            <div class="flex items-center text-red-700">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="font-medium">{{ session('error') }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Success Message Display -->
                                    @if(session('success'))
                                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                            <div class="flex items-center text-green-700">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="font-medium">{{ session('success') }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Return to Cart Link -->
                                    <div class="mb-4">
                                        <a href="{{ route('shop.checkout.cart.index') }}"
                                            class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors font-bold">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                            Return to Cart
                                        </a>
                                    </div>
                                </div>                                   <v-stripe-payment
                    stripe-publishable-key="{{ core()->getConfigData('sales.payment_methods.stripe.stripe_api_publishable_key') }}"
                    grand-total="@if($cart && isset($cart->grand_total)){{ core()->currency($cart->grand_total) }}@else $0.00 @endif"
                ></v-stripe-payment>


 </div>
                        </div>
                        
                        <!-- Order Summary - Right Column (Static) -->
                        <div class="payment-summary-column order-2">
                            <div class="bg-white rounded-2xl shadow-xl p-4 md:p-6" style="width: 100%; min-width: 0;">
                                <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>
                                
                                <!-- Order Items -->
                                @if($cart && $cart->items && $cart->items->count() > 0)
                                    <div class="space-y-4 mb-6">
                                        @foreach($cart->items as $item)
                                            <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-medium text-gray-900 text-sm">{{ $item->name ?? 'Product' }}</h4>
                                                    <p class="text-xs text-gray-500">Qty: {{ $item->quantity ?? 0 }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="font-semibold text-gray-900">
                                                        @if(isset($item->total))
                                                            {{ core()->currency($item->total) }}
                                                        @else
                                                            $0.00
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="mb-6">
                                        <p class="text-gray-500">No items in cart</p>
                                    </div>
                                @endif
                                
                                <!-- Order Totals -->
                                <div class="border-t border-gray-200 pt-4 space-y-3">
                                    <div class="flex justify-between text-gray-600">
                                        <span>Subtotal:</span>
                                        <span class="font-medium">
                                            @if($cart && isset($cart->sub_total))
                                                {{ core()->currency($cart->sub_total) }}
                                            @else
                                                $0.00
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-gray-600">
                                        <span>Shipping:</span>
                                        <span class="font-medium">
                                            @if($cart && isset($cart->selected_shipping_rate->price))
                                                {{ core()->currency($cart->selected_shipping_rate->price) }}
                                            @else
                                                $0.00
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-gray-600">
                                        <span>Tax:</span>
                                        <span class="font-medium">
                                            @if($cart && isset($cart->tax_total))
                                                {{ core()->currency($cart->tax_total) }}
                                            @else
                                                $0.00
                                            @endif
                                        </span>
                                    </div>
                                    @if($cart && isset($cart->discount_amount) && $cart->discount_amount > 0)
                                        <div class="flex justify-between text-green-600">
                                            <span>Discount:</span>
                                            <span class="font-medium">-{{ core()->currency($cart->discount_amount) }}</span>
                                        </div>
                                    @endif
                                    <div class="border-t border-gray-200 pt-3">
                                        <div class="flex justify-between text-xl font-bold text-gray-900">
                                            <span>Total:</span>
                                            <span>
                                                @if($cart && isset($cart->grand_total))
                                                    {{ core()->currency($cart->grand_total) }}
                                                @else
                                                    $0.00
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Security Badge -->
                                <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center text-green-700">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>                                        <div>
                                            <p class="text-sm font-medium">Secure Payment</p>
                                            <p class="text-xs">256-bit SSL encryption</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    @pushOnce('scripts')
    <script src="https://js.stripe.com/v3/"></script>
        <!-- Secure Stripe Elements Form Component Template -->
        <script type="text/x-template" id="v-stripe-payment-template">
            <form @submit.prevent="handleSubmit" ref="paymentForm">
                
                <!-- General Error Display -->
                <div v-show="errors.general" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center text-red-700">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium" v-text="errors.general"></span>
                    </div>
                </div>
                
                <!-- Stripe Card Element Container -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Card Information
                    </label>
                    <div 
                        id="card-element" 
                        class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-all bg-white">
                        <!-- Stripe Elements will be inserted here -->
                    </div>
                    <div v-show="errors.card" class="text-red-500 text-sm mt-2" v-text="errors.card"></div>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    :disabled="isProcessing || !cardComplete"
                    class="w-full bg-navyBlue text-white py-4 px-8 rounded-xl text-lg font-semibold hover:bg-navyBlue hover:opacity-90 focus:ring-4 focus:ring-blue-300 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <span v-show="!isProcessing" class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Complete Secure Payment @{{ grandTotal }}
                    </span>
                    <span v-show="isProcessing" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing Payment...
                    </span>
                </button>
            </form>
        </script>        
        <script type="module">
            // Secure Stripe Elements Payment Form Component
            app.component('v-stripe-payment', {
                template: '#v-stripe-payment-template',
                
                props: {
                    stripePublishableKey: {
                        type: String,
                        required: true
                    },
                    grandTotal: {
                        type: String,
                        default: '$0.00'
                    }
                },
                
                data() {
                    return {
                        isProcessing: false,
                        cardComplete: false,
                        stripe: null,
                        elements: null,
                        cardElement: null,
                        errors: {
                            card: '',
                            general: ''
                        }
                    };
                },
                
                async mounted() {
                    await this.initializeStripe();
                },
                
                methods: {
                    async initializeStripe() {
                        try {
                            // Initialize Stripe
                            this.stripe = Stripe(this.stripePublishableKey);
                            this.elements = this.stripe.elements();
                              // Create card element with custom styling
                            this.cardElement = this.elements.create('card', {
                                style: {
                                    base: {
                                        fontSize: '16px',
                                        color: '#374151',
                                        fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                                        '::placeholder': {
                                            color: '#9CA3AF',
                                        },
                                        iconColor: '#6B7280',
                                    },
                                    invalid: {
                                        color: '#EF4444',
                                        iconColor: '#EF4444',
                                    },
                                },
                                hidePostalCode: true
                            });
                            
                            // Mount the card element
                            this.cardElement.mount('#card-element');
                            
                            // Handle real-time validation errors from the card element
                            this.cardElement.on('change', ({error, complete}) => {
                                this.cardComplete = complete;
                                if (error) {
                                    this.errors.card = error.message;
                                } else {
                                    this.errors.card = '';
                                }
                            });
                            
                        } catch (error) {
                            console.error('Failed to initialize Stripe:', error);
                            this.errors.general = 'Failed to initialize payment system. Please refresh the page.';
                        }
                    },
                    
                    async handleSubmit() {
                        if (!this.stripe || !this.cardElement) {
                            this.errors.general = 'Payment system not initialized. Please refresh the page.';
                            return;
                        }
                        
                        this.isProcessing = true;
                        this.errors.general = '';
                        this.errors.card = '';
                          try {
                            // Step 1: Create PaymentIntent on the server
                            const response = await fetch('/stripe-create-payment-intent', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            
                            // Check if response is JSON
                            const contentType = response.headers.get('content-type');
                            
                            if (!response.ok) {
                                const errorText = await response.text();
                                throw new Error(`Server error ${response.status}: ${errorText}`);
                            }
                            
                            if (!contentType || !contentType.includes('application/json')) {
                                const htmlText = await response.text();
                                throw new Error('Server returned HTML instead of JSON. Check server logs for errors.');
                            }
                            
                            const paymentIntentData = await response.json();
                            
                            if (!response.ok) {
                                throw new Error(paymentIntentData.error || 'Failed to create payment intent');
                            }
                            
                            // Step 2: Confirm payment with Stripe
                            const {error, paymentIntent} = await this.stripe.confirmCardPayment(
                                paymentIntentData.client_secret,
                                {
                                    payment_method: {
                                        card: this.cardElement,
                                    }
                                }
                            );
                            
                            if (error) {
                                // Payment failed
                                this.errors.general = error.message || 'Payment failed. Please try again.';
                                this.isProcessing = false;
                            } else if (paymentIntent.status === 'succeeded') {
                                // Payment succeeded - send to server for order completion
                                await this.completeOrder(paymentIntent.id);                            }
                            
                        } catch (error) {
                            this.errors.general = error.message || 'An error occurred while processing your payment.';
                            this.isProcessing = false;
                        }
                    },
                      async completeOrder(paymentIntentId) {
                        try {
                            // Create a form and submit it to complete the order
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '/stripe-process-payment';
                            
                            // Add CSRF token (this route still needs CSRF)
                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            form.appendChild(csrfInput);
                            
                            // Add payment intent ID
                            const paymentIntentInput = document.createElement('input');
                            paymentIntentInput.type = 'hidden';
                            paymentIntentInput.name = 'payment_intent_id';
                            paymentIntentInput.value = paymentIntentId;
                            form.appendChild(paymentIntentInput);
                            
                            // Submit the form
                            document.body.appendChild(form);
                            form.submit();
                            
                        } catch (error) {
                            this.errors.general = 'Payment successful but order completion failed. Please contact support.';
                            this.isProcessing = false;
                        }}
                }
            });
        </script>
        @endPushOnce

</x-shop::layouts>