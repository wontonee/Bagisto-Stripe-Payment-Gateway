<x-shop::layouts>
    <x-slot:title>
        Complete Your Payment - Stripe
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Complete Your Payment</h1>
                <p class="text-gray-600">Secure payment powered by Stripe</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Order Summary -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Order Summary</h2>
                    
                    <!-- Order Items -->
                    <div class="space-y-3 mb-4">                        @if($cart && $cart->items)
                            @foreach($cart->items as $item)
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-800">{{ $item->name }}</h4>
                                    <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-800">{{ core()->currency($item->total) }}</p>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Order Totals -->
                    <div class="border-t border-gray-300 pt-4 space-y-2">                        @if($cart)
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal:</span>
                            <span>{{ core()->currency($cart->sub_total) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping:</span>
                            <span>{{ core()->currency($cart->selected_shipping_rate ? $cart->selected_shipping_rate->price : 0) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Tax:</span>
                            <span>{{ core()->currency($cart->tax_total) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-gray-800 border-t border-gray-300 pt-2">
                            <span>Total:</span>
                            <span>{{ core()->currency($cart->grand_total) }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Payment Information</h2>
                    
                    <!-- Stripe Logo -->
                    <div class="flex items-center justify-center mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center">
                                <span class="text-white font-bold text-sm">S</span>
                            </div>
                            <span class="text-lg font-semibold text-gray-700">Secure Payment by Stripe</span>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <div class="w-8 h-5 bg-blue-600 rounded text-white text-xs flex items-center justify-center font-bold">VISA</div>
                            <div class="w-8 h-5 bg-red-500 rounded text-white text-xs flex items-center justify-center font-bold">MC</div>
                            <div class="w-8 h-5 bg-blue-500 rounded text-white text-xs flex items-center justify-center font-bold">AX</div>
                        </div>
                    </div>                    <form id="stripe-payment-form" method="POST" action="{{ route('stripe.process.payment') }}">
                        @csrf
                        
                        <!-- Card Number -->
                        <div class="mb-6">
                            <label for="card-number" class="block text-sm font-medium text-gray-700 mb-2">
                                Card Number *
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="card-number" 
                                       name="card_number"
                                       placeholder="1234 5678 9012 3456"
                                       maxlength="19"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-lg"
                                       required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <div id="card-brand" class="text-gray-400 font-semibold"></div>
                                </div>
                            </div>
                            <div id="card-number-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <!-- Expiry Date -->
                            <div>
                                <label for="card-expiry" class="block text-sm font-medium text-gray-700 mb-2">
                                    Expiry Date *
                                </label>
                                <input type="text" 
                                       id="card-expiry" 
                                       name="card_expiry"
                                       placeholder="MM/YY"
                                       maxlength="5"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-lg"
                                       required>
                                <div id="card-expiry-error" class="text-red-500 text-sm mt-1 hidden"></div>
                            </div>

                            <!-- CVV -->
                            <div>
                                <label for="card-cvv" class="block text-sm font-medium text-gray-700 mb-2">
                                    CVV *
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           id="card-cvv" 
                                           name="card_cvv"
                                           placeholder="123"
                                           maxlength="4"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-lg"
                                           required>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <div class="text-gray-400 text-sm cursor-help" title="3-4 digit security code on the back of your card">?</div>
                                    </div>
                                </div>
                                <div id="card-cvv-error" class="text-red-500 text-sm mt-1 hidden"></div>
                            </div>
                        </div>

                        <!-- Security Notice -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">
                                        <strong>Secure Payment:</strong> Your payment information is encrypted and secure. We don't store your card details.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                id="submit-payment"
                                class="w-full bg-blue-600 text-white py-4 px-6 rounded-lg text-lg font-semibold hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">                            <span id="button-text">
                                Complete Payment {{ $cart ? core()->currency($cart->grand_total) : '' }}
                            </span>
                            <span id="button-spinner" class="hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>

                        <!-- Cancel Link -->
                        <div class="text-center mt-4">
                            <a href="{{ route('shop.checkout.cart.index') }}" 
                               class="text-gray-600 hover:text-gray-800 underline">
                                ← Return to Cart
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Format card number input
            const cardNumberInput = document.getElementById('card-number');
            cardNumberInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
                if (formattedValue.length > 19) formattedValue = formattedValue.substr(0, 19);
                e.target.value = formattedValue;
                
                // Update card brand
                updateCardBrand(value);
            });

            // Format expiry date input
            const cardExpiryInput = document.getElementById('card-expiry');
            cardExpiryInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length >= 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2, 4);
                }
                e.target.value = value;
            });            // Format CVC input (renamed from card-cvc to card-cvv)
            const cardCvvInput = document.getElementById('card-cvv');
            cardCvvInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '');
            });

            // Update card brand display
            function updateCardBrand(cardNumber) {
                const cardBrandElement = document.getElementById('card-brand');
                const cleanNumber = cardNumber.replace(/\s/g, '');
                
                if (cleanNumber.match(/^4/)) {
                    cardBrandElement.textContent = 'VISA';
                    cardBrandElement.className = 'text-blue-600 font-semibold';
                } else if (cleanNumber.match(/^5[1-5]/)) {
                    cardBrandElement.textContent = 'MC';
                    cardBrandElement.className = 'text-red-600 font-semibold';
                } else if (cleanNumber.match(/^3[47]/)) {
                    cardBrandElement.textContent = 'AMEX';
                    cardBrandElement.className = 'text-blue-500 font-semibold';
                } else {
                    cardBrandElement.textContent = '';
                    cardBrandElement.className = 'text-gray-400';
                }
            }

            // Form submission handling
            const form = document.getElementById('stripe-payment-form');
            const submitButton = document.getElementById('submit-payment');
            const buttonText = document.getElementById('button-text');
            const buttonSpinner = document.getElementById('button-spinner');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show loading state
                submitButton.disabled = true;
                buttonText.classList.add('hidden');
                buttonSpinner.classList.remove('hidden');
                
                // Basic validation
                if (validateForm()) {
                    // Here we'll add Stripe processing later
                    setTimeout(() => {
                        // Reset button state for now
                        submitButton.disabled = false;
                        buttonText.classList.remove('hidden');
                        buttonSpinner.classList.add('hidden');
                        alert('Payment form is ready! Next step: integrate with Stripe API.');
                    }, 2000);
                } else {
                    // Reset button state
                    submitButton.disabled = false;
                    buttonText.classList.remove('hidden');
                    buttonSpinner.classList.add('hidden');
                }
            });

            function validateForm() {
                let isValid = true;
                
                // Clear previous errors
                document.querySelectorAll('[id$="-error"]').forEach(el => {
                    el.classList.add('hidden');
                });

                // Validate card number
                const cardNumber = cardNumberInput.value.replace(/\s/g, '');
                if (cardNumber.length < 13 || cardNumber.length > 19) {
                    showError('card-number-error', 'Please enter a valid card number');
                    isValid = false;
                }

                // Validate expiry
                const expiry = cardExpiryInput.value;
                if (!/^\d{2}\/\d{2}$/.test(expiry)) {
                    showError('card-expiry-error', 'Please enter a valid expiry date (MM/YY)');
                    isValid = false;
                }                // Validate CVV
                const cvv = cardCvvInput.value;
                if (cvv.length < 3 || cvv.length > 4) {
                    showError('card-cvv-error', 'Please enter a valid CVV');
                    isValid = false;
                }

                return isValid;
            }

            function showError(elementId, message) {
                const errorElement = document.getElementById(elementId);
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            }
        });
    </script>
    @endPushOnce
</x-shop::layouts>