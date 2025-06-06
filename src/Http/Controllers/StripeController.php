<?php

namespace Wontonee\Stripe\Http\Controllers;

use Illuminate\Routing\Controller;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\Sales\Repositories\InvoiceRepository;
use Illuminate\Http\Request;
use Stripe;

class StripeController extends Controller
{

  /**
   * OrderRepository $orderRepository
   *
   * @var \Webkul\Sales\Repositories\OrderRepository
   */
  protected $orderRepository;
  /**
   * InvoiceRepository $invoiceRepository
   *
   * @var \Webkul\Sales\Repositories\InvoiceRepository
   */
  protected $invoiceRepository;

  /**
   * Create a new controller instance.
   *
   * @param  \Webkul\Attribute\Repositories\OrderRepository  $orderRepository
   * @return void
   */
  public function __construct(OrderRepository $orderRepository,  InvoiceRepository $invoiceRepository)
  {
    $this->orderRepository = $orderRepository;
    $this->invoiceRepository = $invoiceRepository;
  }

  /**
   * Redirects to the paytm server.
   *
   * @return \Illuminate\View\View
   */

  public function redirect()
  {
    return redirect()->route('stripe.payment.card'); // redirect to payment card view
  }
  /**
   * Show the payment card form
   *
   * @return \Illuminate\View\View
   */
  public function showPaymentCard()
  {
    $cart = Cart::getCart();

    if (! $cart) {
      return redirect()->route('shop.checkout.cart.index')
        ->with('error', 'Cart not found');
    }

    // Check if Stripe is configured
    $apiKey = core()->getConfigData('sales.payment_methods.stripe.stripe_api_key');
    if (!$apiKey) {
      return redirect()->route('shop.checkout.cart.index')
        ->with('error', 'Stripe payment method is not configured');
    }

    $customer = auth()->guard('customer')->user();

    return view('stripe::payment.index', compact('cart', 'customer'));
  }
  /**
   * Create PaymentIntent for secure payment processing
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function createPaymentIntent(Request $request)
  {
    try {
      // Check if cart exists before proceeding
      $cart = null;
      try {
        $cart = Cart::getCart();
      } catch (\Exception $cartException) {
        return response()->json(['error' => 'Failed to retrieve cart information: ' . $cartException->getMessage()], 400);
      }
      if (!$cart) {
        return response()->json([
          'error' => 'No active cart found. Please add items to cart first.',
          'suggestion' => 'Navigate to the shop, add items to cart, then proceed to checkout before making payment.'
        ], 400);
      }

      // Calculate totals
      $shipping_rate = $cart->selected_shipping_rate ? $cart->selected_shipping_rate->price : 0;
      $discount_amount = $cart->discount_amount ?? 0;
      $total_amount = ($cart->sub_total + $cart->tax_total + $shipping_rate) - $discount_amount;      // Get API key
      $apiKey = core()->getConfigData('sales.payment_methods.stripe.stripe_api_key');

      if (!$apiKey) {
        return response()->json([
          'error' => 'Stripe payment method is not configured. Please configure the API key in admin settings.',
          'suggestion' => 'Contact administrator to configure Stripe payment settings.'
        ], 500);
      }

      Stripe\Stripe::setApiKey($apiKey);

      $customer = auth()->guard('customer')->user();
      $billingAddress = $cart->billing_address;      // Prepare customer and shipping information for Indian export compliance
      $paymentIntentData = [
        'amount' => (int) ($total_amount * 100), // Convert to cents
        'currency' => strtolower($cart->global_currency_code ?? 'usd'),
        'payment_method_types' => ['card'],
        'description' => 'Payment for Order #' . $cart->id,
        'metadata' => [
          'cart_id' => $cart->id,
          'integration' => 'bagisto_stripe'
        ],
      ];

      // Get customer information
      $customerName = '';
      $customerEmail = '';

      if ($customer) {
        $customerName = trim($customer->first_name . ' ' . $customer->last_name);
        $customerEmail = $customer->email;
      } elseif ($billingAddress) {
        $customerName = trim($billingAddress->first_name . ' ' . $billingAddress->last_name);
        $customerEmail = $billingAddress->email ?? '';
      }

      // Ensure we have a customer name (required for Indian export compliance)
      if (empty($customerName)) {
        $customerName = 'Customer Name'; // More descriptive fallback
      }

      // Add customer information to metadata
      $paymentIntentData['metadata']['customer_name'] = $customerName;
      if (!empty($customerEmail)) {
        $paymentIntentData['metadata']['customer_email'] = $customerEmail;
      }

      // Prepare address data for Indian export compliance
      $addressData = [
        'name' => $customerName,
        'address' => [
          'line1' => 'Address Line 1', // Default fallback
          'city' => 'City',
          'state' => 'State',
          'postal_code' => '000000',
          'country' => 'US', // Default to US
        ],
      ];

      // Use actual billing address if available
      if ($billingAddress) {
        $addressData['address'] = [
          'line1' => $billingAddress->address1 ?: 'Address Line 1',
          'city' => $billingAddress->city ?: 'City',
          'state' => $billingAddress->state ?: 'State',
          'postal_code' => $billingAddress->postcode ?: '000000',
          'country' => $billingAddress->country ?: 'US',
        ];

        // Add line2 only if it exists and is non-empty
        if (!empty($billingAddress->address2)) {
          $addressData['address']['line2'] = $billingAddress->address2;
        }

        // Also add to metadata for additional compliance
        $paymentIntentData['metadata']['billing_address'] =
          $addressData['address']['line1'] . ', ' .
          $addressData['address']['city'] . ', ' .
          $addressData['address']['state'] . ', ' .
          $addressData['address']['country'];
      }

      // Set shipping address (required for Indian export compliance)
      $paymentIntentData['shipping'] = $addressData;

      // Add receipt_email for better compliance tracking
      if (!empty($customerEmail)) {
        $paymentIntentData['receipt_email'] = $customerEmail;
      }

      // Enhanced metadata for Indian export compliance
      $paymentIntentData['metadata']['export_compliance'] = 'indian_regulations';
      $paymentIntentData['metadata']['transaction_type'] = 'ecommerce_payment';
      $paymentIntentData['metadata']['platform'] = 'bagisto';

      // Add customer phone if available
      if ($customer && !empty($customer->phone)) {
        $paymentIntentData['metadata']['customer_phone'] = $customer->phone;
      } elseif ($billingAddress && !empty($billingAddress->phone)) {
        $paymentIntentData['metadata']['customer_phone'] = $billingAddress->phone;
      } // Create PaymentIntent
      $paymentIntent = Stripe\PaymentIntent::create($paymentIntentData);

      return response()->json([
        'client_secret' => $paymentIntent->client_secret,
        'payment_intent_id' => $paymentIntent->id
      ]);
    } catch (\Stripe\Exception\CardException $e) {
      return response()->json(['error' => 'Card error: ' . $e->getMessage()], 400);
    } catch (\Stripe\Exception\RateLimitException $e) {
      return response()->json(['error' => 'Rate limit exceeded'], 429);
    } catch (\Stripe\Exception\InvalidRequestException $e) {
      return response()->json(['error' => 'Invalid request: ' . $e->getMessage()], 400);
    } catch (\Stripe\Exception\AuthenticationException $e) {
      return response()->json(['error' => 'Authentication failed'], 401);
    } catch (\Stripe\Exception\ApiConnectionException $e) {
      return response()->json(['error' => 'Network error'], 500);
    } catch (\Stripe\Exception\ApiErrorException $e) {
      return response()->json(['error' => 'API error: ' . $e->getMessage()], 500);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Process payment with card details
   *
   * @return \Illuminate\Http\RedirectResponse
   */
  public function processPayment(Request $request)
  {

    try {
      // Validate request data - expecting PaymentIntent ID from Stripe.js
      $request->validate([
        'payment_intent_id' => 'required|string',
      ]);

      $cart = Cart::getCart();

      if (!$cart) {
        return redirect()->route('shop.checkout.cart.index')
          ->with('error', 'Cart not found');
      }

      // Set Stripe API key
      $apiKey = core()->getConfigData('sales.payment_methods.stripe.stripe_api_key');
      if (!$apiKey) {
        throw new \Exception('Stripe API key not configured');
      }

      Stripe\Stripe::setApiKey($apiKey);

      // Retrieve the PaymentIntent to verify it was successful
      $paymentIntent = Stripe\PaymentIntent::retrieve($request->input('payment_intent_id'));

      if ($paymentIntent->status === 'succeeded') {
        // Store payment details in session for order creation
        session([
          'stripe_payment_id' => $paymentIntent->id,
          'stripe_customer_id' => $paymentIntent->customer,
        ]);

        return redirect()->route('stripe.success');
      } else {
        throw new \Exception('Payment failed: Payment not completed');
      }
    } catch (Stripe\Exception\CardException $e) {
      return redirect()->back()
        ->with('error', 'Card error: ' . $e->getError()->message)
        ->withInput();
    } catch (Stripe\Exception\RateLimitException $e) {
      return redirect()->back()
        ->with('error', 'Too many requests made to the API too quickly')
        ->withInput();
    } catch (Stripe\Exception\InvalidRequestException $e) {
      return redirect()->back()
        ->with('error', 'Invalid parameters: ' . $e->getError()->message)
        ->withInput();
    } catch (Stripe\Exception\AuthenticationException $e) {
      return redirect()->back()
        ->with('error', 'Authentication with Stripe\'s API failed')
        ->withInput();
    } catch (Stripe\Exception\ApiConnectionException $e) {
      return redirect()->back()
        ->with('error', 'Network communication with Stripe failed')
        ->withInput();
    } catch (Stripe\Exception\ApiErrorException $e) {
      return redirect()->back()
        ->with('error', 'Stripe API error: ' . $e->getError()->message)
        ->withInput();
    } catch (\Exception $e) {
      return redirect()->back()
        ->with('error', 'Payment processing failed: ' . $e->getMessage())
        ->withInput();
    }
  }

  /**
   * success
   */
  public function success()
  {

    $cart = Cart::getCart();

    if (!$cart) {
      return redirect()->route('shop.checkout.cart.index')
        ->with('error', 'Cart not found');
    }

    try {
      $data = (new OrderResource($cart))->jsonSerialize();
      $order = $this->orderRepository->create($data);

      // Update order status
      $this->orderRepository->update(['status' => 'processing'], $order->id);

      // Create invoice if the order can be invoiced
      if ($order->canInvoice()) {
        $this->invoiceRepository->create($this->prepareInvoiceData($order));
      }

      // Deactivate cart
      Cart::deActivateCart();
      // Store order ID in session
      session()->flash('order_id', $order->id);
      return redirect()->route('shop.checkout.onepage.success');
    } catch (\Exception $e) {
      return redirect()->route('shop.checkout.cart.index')
        ->with('error', 'Order creation failed: ' . $e->getMessage());
    }
  }

  /**
   * Prepares order's invoice data for creation.
   *
   * @return array
   */
  protected function prepareInvoiceData($order)
  {
    $invoiceData = ["order_id" => $order->id,];

    foreach ($order->items as $item) {
      $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
    }

    return $invoiceData;
  }
}
