<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Safepay\SafepayClient;

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

    public function SafepayCheckout(Request $request)
    {
        try {
            $safepay = new SafepayClient([
                'api_key' => env('SAFEPAY_API_KEY'),  // Public key (sec_) for order setup
                'api_base' => env('SAFEPAY_API_BASE'),
            ]);

            // Validate amount (in PKR)
            // $validated = $request->validate([
            //     'amount' => 'required|numeric|min:1',
            // ]);

            // Create payment session (tracker)
            $session = $safepay->order->setup([
                'merchant_api_key' => env('SAFEPAY_API_KEY'),  // Public key (sec_)
                'intent' => 'CYBERSOURCE',
                'mode' => 'payment',
                'currency' => 'PKR',
                'amount' => 110 * 100,  // Convert to paisa
            ]);

            // Create separate client for passport with secret key
            $safepay_passport = new SafepayClient([
                'api_key' => env('SAFEPAY_SECRET_KEY'),  // Secret key (hex) for passport auth
                'api_base' => env('SAFEPAY_API_BASE'),
            ]);

            // Create temporary bearer token (TBT)
            $tbt = $safepay_passport->passport->create();

            // Construct hosted redirect URL
            $checkoutURL = \Safepay\Checkout::constructURL([
                'environment' => 'sandbox',
                'tracker' => $session->tracker->token,
                'tbt' => $tbt->token,
                'source' => 'website',
                'cancel_url' => 'https://pakfumes.com/cancel',
                'redirect_url' => 'https://pakfumes.com/success'
            ]);

            // Return URL to frontend
            return response()->json(['url' => $checkoutURL]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
