<?php
// app/Http/Controllers/KhaltiController.php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class KhaltiController extends Controller
{
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'tax_amount' => 'nullable|numeric|min:0',
            'product_service_charge' => 'nullable|numeric|min:0',
            'product_delivery_charge' => 'nullable|numeric|min:0',
        ]);

        $totalAmount = $validated['amount'] +
            ($validated['tax_amount'] ?? 0) +
            ($validated['product_service_charge'] ?? 0) +
            ($validated['product_delivery_charge'] ?? 0);

        $paymentData = [
            'product_name' => $request->product_name,
            'amount' => $validated['amount'],
            'tax_amount' => $validated['tax_amount'] ?? 0,
            'product_service_charge' => $validated['product_service_charge'] ?? 0,
            'product_delivery_charge' => $validated['product_delivery_charge'] ?? 0,
            'total_amount' => $totalAmount,
        ];
        return view('payments.khalti-checkout', [
            'paymentData' => $paymentData,
        ]);
    }

    public function initiatePayment(Request $request)
    {
        $purchaseOrderId = 'ORDER-' . Str::random(8);
        $payload = [
            'return_url' => route('khalti.payment.verify'),
            'website_url' => config('app.url'),
            'amount' => $request->total_amount * 100, // Convert to paisa
            'purchase_order_id' => $purchaseOrderId,
            'purchase_order_name' => $request->product_name ?? 'Product Purchase',
            'customer_info' => [
                'name' => Auth::user()->name ?? 'Guest Customer',
                'email' => Auth::user()->email ?? 'guest@example.com',
            ],
            'product_details' => [
                [
                    'identity' => 'ORDER-' . Str::random(8),
                    'name' => $request->product_name ?? 'Product Purchase',
                    'total_price' => $request->total_amount * 100,
                    'quantity' => 1,
                    'unit_price' => $request->total_amount * 100
                ]
            ]
        ];
        try {
            $client = new Client();
            $response = $client->post('https://a.khalti.com/api/v2/epayment/initiate/', [
                'json' => $payload,
                'headers' => [
                    'Authorization' => 'Key ' . config('services.khalti.secret_key')
                ]
            ]);
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                return redirect($data['payment_url']);
            } else {
                return back()->with('error', 'Failed to initiate payment. Please try again.');
            }
        } catch (\Exception $ex) {
            //return response()->json($ex->getResponse()->getBody(), $ex->getCode());
            Log::error('Khalti Payment Error: ' . $ex->getMessage());
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    public function verifyPayment(Request $request)
    {
        if ($request->status === 'Completed') {
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . config('services.khalti.secret_key')
            ])->post('https://a.khalti.com/api/v2/epayment/lookup/', [
                'pidx' => $request->pidx
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] === 'Completed') {
                    // Payment successful - update your database here
                    return redirect()->route('khalti.success')
                        ->with('success', 'Payment completed successfully!');
                }
            }
        }

        return redirect()->route('khalti.failure')
            ->with('error', 'Payment verification failed. Status: ' . $request->status);
    }

    public function success(Request $request)
    {
        // Update your order status here
        // You might want to dispatch a job here to handle the payment success

        $successMessage = $request->session()->get('success', 'Payment completed successfully');
        return view('payments.khalti-success', ['message' => $successMessage]);
    }

    public function failure(Request $request)
    {
        $errorMessage = $request->session()->get('error', 'Payment failed or was cancelled');
        return view('payments.khalti-failure', ['error' => $errorMessage]);
    }
}
