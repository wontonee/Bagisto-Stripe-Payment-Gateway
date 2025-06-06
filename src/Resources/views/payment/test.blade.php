<x-shop::layouts>
    <x-slot:title>
        Payment Test
    </x-slot>

    <div class="container mx-auto p-8">
        <h1 class="text-2xl font-bold mb-4">Payment Test Page</h1>
        
        @if($cart)
            <div class="bg-green-100 p-4 rounded mb-4">
                <h2 class="font-semibold">Cart Found!</h2>
                <p>Cart ID: {{ $cart->id ?? 'N/A' }}</p>
                <p>Grand Total: {{ $cart->grand_total ?? 'N/A' }}</p>
                <p>Sub Total: {{ $cart->sub_total ?? 'N/A' }}</p>
                <p>Tax Total: {{ $cart->tax_total ?? 'N/A' }}</p>
                <p>Discount Amount: {{ $cart->discount_amount ?? 'N/A' }}</p>
                
                @if($cart->selected_shipping_rate)
                    <p>Shipping Rate: {{ $cart->selected_shipping_rate->price ?? 'N/A' }}</p>
                @else
                    <p>Shipping Rate: Not selected</p>
                @endif
                
                @if($cart->items)
                    <p>Items Count: {{ count($cart->items) }}</p>
                @else
                    <p>No items in cart</p>
                @endif
            </div>
            
            <!-- Test the core()->currency function -->
            <div class="bg-blue-100 p-4 rounded mb-4">
                <h2 class="font-semibold">Currency Function Tests:</h2>
                <p>Test 1: {{ core()->currency(100) }}</p>
                <p>Test 2: {{ core()->currency($cart->grand_total ?? 0) }}</p>
                <p>Test 3: {{ core()->currency($cart->sub_total ?? 0) }}</p>
            </div>
            
        @else
            <div class="bg-red-100 p-4 rounded">
                <p>No cart found!</p>
            </div>
        @endif
        
        <div class="mt-4">
            <a href="{{ route('shop.checkout.cart.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
                Back to Cart
            </a>
        </div>
    </div>
</x-shop::layouts>
