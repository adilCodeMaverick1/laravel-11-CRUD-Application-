<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Safepay\SafepayClient;
use App\Models\Order;
use Safepay\Checkout;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{


    public function pageCheckout()
    {
        return view('checkout');
    }
    public function initiatePayment(Request $request)
    {
        // 1. Get order details from the request
        $orderId = 'ORD-' . time(); // Unique order ID
        $amount = $request->input('amount');
        $customerEmail = $request->input('email');

        // 2. Prepare data for PayFast
        $data = [
            'merchant_id' => env('PAYFAST_MERCHANT_ID'),
            'amount' => $request->input('amount'),
            'reference' => 'ORD-' . time(),
            'description' => 'E-commerce Order', // This is optional, but good practice
            'return_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
            'notify_url' => route('payment.notify'),
            'email' => $request->input('email'),
            'item_name' => 'Product Purchase', // Required by PayFast PK
            'merchant_key' => env('PAYFAST_SECURE_KEY'), // Required by PayFast PK
        ];

        // 3. Generate the signature
        // The signature logic for PayFast Pakistan is different.
        // PayFast PK requires the merchant_key to be part of the data.
        // The signature itself should NOT be part of the signature generation data.
        $signature = $this->generateSignature($data);

        // 4. Redirect to PayFast
        $payFastUrl = env('PAYFAST_IS_TEST_MODE') ?
            'https://sandbox.payfast.pk/checkout' :
            'https://secure.payfast.pk/checkout';

        $data['signature'] = $signature;

        // Build the query string for redirection
        $queryString = http_build_query($data);

        return redirect()->to($payFastUrl . '?' . $queryString);
    }

    // private function generateSignature($data)
    // {
    //     // Sort the data alphabetically by key
    //     ksort($data);

    //     // Create a query string
    //     $queryString = http_build_query($data);

    //     // Append the Secure Key
    //     $fullString = $queryString . '&' . env('PAYFAST_SECURE_KEY');

    //     // Generate and return the MD5 hash
    //     return md5($fullString);
    // }
    private function generateSignature($data)
    {
        // The 'signature' key should not be part of the data used for hashing
        // if ($data['signature']) {
        //     unset($data['signature']);
        // }

        // Sort the data alphabetically by key
        ksort($data);

        // Create a query string
        $queryString = http_build_query($data);

        // Append the Secure Key
        // Note: The Secure Key is also part of the query string now, as 'merchant_key'.
        // The documentation for PayFast Pakistan may differ on this step, so double-check.
        // However, the 400 error implies the key must be sent as a separate field.
        // A simpler and more common approach is just to sort and hash the entire data array.

        return md5($queryString);
    }
    public function handleSuccess(Request $request)
    {
        // This is where the user lands after a successful payment
        // You should still verify the IPN notification before updating the order status.
        return view('payment.success');
    }

    public function handleCancel(Request $request)
    {
        // This is where the user lands if they cancel the payment
        return view('payment.cancel');
    }

    public function handleNotification(Request $request)
    {
        // 1. Get all the data sent by PayFast
        $post_data = $request->all();

        // 2. Generate signature on your end and verify it matches the one from PayFast
        $receivedSignature = $post_data['signature'];
        unset($post_data['signature']);
        $generatedSignature = $this->generateSignature($post_data);

        if ($receivedSignature === $generatedSignature) {
            // Signature is valid, now check payment status
            if ($post_data['payment_status'] === 'Completed') {
                // Payment is successful, update your order in the database
                $orderId = $post_data['reference'];
                // Update order status logic goes here...
                \Log::info("Payment successful for order: " . $orderId);
            }
        } else {
            // Invalid signature, log it for security
            \Log::error("Invalid IPN signature received.");
        }

        return response('OK', 200);
    }


    // public function SafepayCheckout(Request $request)
    // {
    //     try {
    //         $safepay = new SafepayClient([
    //             'api_key' => env('SAFEPAY_API_KEY'),  // sec_ key for auth
    //             'api_base' => env('SAFEPAY_API_BASE'),  // Correct URL
    //         ]);

    //         // Validate request data (e.g., amount from cart)
    //         // $validated = $request->validate([
    //         //     'amount' => 'required|numeric|min:100',  // In paisa
    //         // ]);

    //         // Create payment session
    //         $tracker = $safepay->order->setup([
    //             'merchant_api_key' => env('SAFEPAY_API_KEY'),  // Same sec_ key
    //             'intent' => 'CYBERSOURCE',  // For card payments
    //             'mode' => 'payment',  // Use 'unscheduled_cof' if needed
    //             'currency' => 'PKR',
    //             'amount' => 130 * 100,  // Convert to paisa if input is in PKR
    //         ]);

    //         // Return tracker to frontend (Nuxt)
    //         return response()->json([
    //             'tracker' => $tracker->tracker->token,  // e.g., "track_xxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
    //         ]);
    //     } catch (\Exception $e) {
    //         // Handle errors (e.g., log and return error response)
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    // public function SafepayCheckout(Request $request)
    // {
    //     try {
    //         $safepay = new SafepayClient([
    //             'api_key' => env('SAFEPAY_API_KEY'),  // Public key (sec_) for order setup
    //             'api_base' => env('SAFEPAY_API_BASE'),
    //         ]);

    //         // Validate amount (in PKR)
    //         // $validated = $request->validate([
    //         //     'amount' => 'required|numeric|min:1',
    //         // ]);

    //         // Create payment session (tracker)
    //         $session = $safepay->order->setup([
    //             'merchant_api_key' => env('SAFEPAY_API_KEY'),  // Public key (sec_)
    //             'intent' => 'CYBERSOURCE',
    //             'mode' => 'payment',
    //             'currency' => 'PKR',
    //             'amount' => 110 * 100,  // Convert to paisa
    //         ]);

    //         // Create separate client for passport with secret key
    //         $safepay_passport = new SafepayClient([
    //             'api_key' => env('SAFEPAY_SECRET_KEY'),  // Secret key (hex) for passport auth
    //             'api_base' => env('SAFEPAY_API_BASE'),
    //         ]);

    //         // Create temporary bearer token (TBT)
    //         $tbt = $safepay_passport->passport->create();

    //         // Construct hosted redirect URL
    //         $checkoutURL = \Safepay\Checkout::constructURL([
    //             'environment' => 'sandbox',
    //             'tracker' => $session->tracker->token,
    //             'tbt' => $tbt->token,
    //             'source' => 'website',
    //             'cancel_url' => 'https://pakfumes.com/cancel',
    //             'redirect_url' => 'https://pakfumes.com/success'
    //         ]);

    //         // Return URL to frontend
    //         return response()->json(['url' => $checkoutURL]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
    public function SafepayCheckout(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:1',
                'vendor_id' => 'required|integer',
            ]);

            $amountPaisa = (int) round($request->amount * 100);

            // 1) Create pending order
            $order = Order::create([
                'vendor_id' => $request->vendor_id,
                'user_id' => 1,
                'amount' => $amountPaisa,
                'status' => 'pending',
                'payment_method' => 'safepay',
            ]);

            // 2) Safepay client (public key)
            $safepay = new SafepayClient([
                'api_key' => env('SAFEPAY_API_KEY'),
                'api_base' => env('SAFEPAY_API_BASE'),
            ]);

            $session = $safepay->order->setup([
                'merchant_api_key' => env('SAFEPAY_API_KEY'),
                'intent' => 'CYBERSOURCE',
                'mode' => 'payment',
                'currency' => 'PKR',
                'amount' => $amountPaisa,
            ]);

            // 3) Save tracker to order
            $order->update(['tracker' => $session->tracker->token]);

            // 4) Secret key for passport (TBT)
            $safepay_passport = new SafepayClient([
                'api_key' => env('SAFEPAY_SECRET_KEY'),
                'api_base' => env('SAFEPAY_API_BASE'),
            ]);

            $tbt = $safepay_passport->passport->create();

            // 5) Construct checkout URL with proper redirect URLs
            $successUrl = route('payment.success', $order->id);
            $cancelUrl = route('payment.cancel', $order->id);

            \Log::info('SafePay checkout URL construction', [
                'order_id' => $order->id,
                'tracker' => $session->tracker->token,
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl
            ]);

            $checkoutURL = Checkout::constructURL([
                'environment' => env('SAFEPAY_ENV', 'sandbox'),
                'tracker' => $session->tracker->token,
                'tbt' => $tbt->token,
                'source' => 'hosted',
                'cancel_url' => $cancelUrl,
                'redirect_url' => $successUrl,
            ]);

            \Log::info('Generated SafePay checkout URL', ['url' => $checkoutURL]);

            return response()->json(['url' => $checkoutURL]);

        } catch (\Exception $e) {
            \Log::error('Safepay checkout error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment setup failed'], 500);
        }
    }

    // Step 2: Success redirect → verify payment
    // public function paymentSuccess(Request $request, $orderId)
    // {
    //     $order = Order::findOrFail($orderId);

    //     try {
    //         $safepay = new SafepayClient([
    //             'api_key' => env('SAFEPAY_SECRET_KEY'), // secret key for verification
    //             'api_base' => env('SAFEPAY_API_BASE'),
    //         ]);

    //         // Verify tracker status
    //         $status = $safepay->order->verify(['tracker' => $order->tracker]);

    //         if ($status->status === 'PAID') {
    //             $order->update(['status' => 'paid']);
    //             return redirect()->route('order.show', $orderId)
    //                 ->with('success', 'Payment successful');
    //         } else {
    //             $order->update(['status' => 'failed']);
    //             return redirect()->route('order.show', $orderId)
    //                 ->with('error', 'Payment failed');
    //         }
    //     } catch (\Exception $e) {
    //         return redirect()->route('order.show', $orderId)
    //             ->with('error', 'Verification error');
    //     }
    // }
    public function paymentSuccess(Request $request, $orderId)
    {
        // Add headers to prevent CloudFlare timeout issues
        header('Connection: keep-alive');
        header('Keep-Alive: timeout=300, max=1000');

        $order = Order::findOrFail($orderId);
        \Log::info('Payment success redirect received', [
            'order_id' => $orderId,
            'tracker' => $order->tracker,
            'url_params' => $request->all()
        ]);
        exit;

        // Get tracker from URL params (SafePay appends ?tracker=track_...&reference=... on success)
        $tracker = $request->query('tracker');
        $reference = $request->query('reference');  // Optional: Store for reconciliation
        $tbt = $request->query('tbt'); // Get TBT from URL

        if (!$tracker) {
            \Log::error('SafePay verification failed: No tracker found', [
                'order_id' => $orderId,
                'url_params' => $request->all()
            ]);
            return redirect()->route('order.show', $orderId)
                ->with('error', 'Payment verification failed - missing tracker');
        }

        try {
            // Use HTTP verification as primary method since SDK verify() doesn't exist
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('SAFEPAY_SECRET_KEY'),
                'Content-Type' => 'application/json',
            ])->post(env('SAFEPAY_API_BASE') . '/order/v1/verify', [
                        'tracker' => $tracker,
                    ]);

            \Log::info('SafePay verification request', [
                'order_id' => $orderId,
                'tracker' => $tracker,
                'response_status' => $response->status(),
                'response_body' => $response->json()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // Handle different response structures
                $paymentState = $data['data']['status'] ?? $data['data']['state'] ?? $data['status'] ?? $data['state'] ?? null;

                if ($paymentState === 'PAID' || $paymentState === 'TRACKER_COMPLETED') {  // Success state
                    $order->update([
                        'status' => 'paid',
                        'payment_reference' => $reference ?: ($data['data']['reference'] ?? $data['reference'] ?? null),
                        'verified_at' => now()
                    ]);

                    \Log::info('Payment successfully verified and marked as paid', [
                        'order_id' => $orderId,
                        'tracker' => $tracker,
                        'reference' => $reference
                    ]);

                    return redirect()->route('order.show', $orderId)
                        ->with('success', 'Payment verified and marked as Paid ✅');
                } else {
                    $order->update(['status' => 'failed']);

                    \Log::warning('Payment verification returned non-completed state', [
                        'order_id' => $orderId,
                        'tracker' => $tracker,
                        'state' => $paymentState
                    ]);

                    return redirect()->route('order.show', $orderId)
                        ->with('error', 'Payment failed - State: ' . ($paymentState ?: 'Unknown'));
                }
            } else {
                \Log::error('SafePay API verification failed', [
                    'order_id' => $orderId,
                    'tracker' => $tracker,
                    'status_code' => $response->status(),
                    'response_body' => $response->body()
                ]);

                return redirect()->route('order.show', $orderId)
                    ->with('error', 'Payment verification failed - API Error: ' . $response->status());
            }

        } catch (\Exception $e) {
            \Log::error('SafePay verification exception', [
                'order_id' => $orderId,
                'tracker' => $tracker,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('order.show', $orderId)
                ->with('error', 'Payment verification failed - ' . $e->getMessage());
        }
    }


    // Step 3: Cancel redirect
    public function paymentCancel($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'failed']);
        return redirect()->route('order.show', $orderId)
            ->with('error', 'Payment cancelled');
    }
}
