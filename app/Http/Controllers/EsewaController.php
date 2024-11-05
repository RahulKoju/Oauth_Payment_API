<?php
// app/Http/Controllers/EsewaController.php

namespace App\Http\Controllers;

use App\Services\EsewaService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EsewaController extends Controller
{
    private $esewaService;

    public function __construct(EsewaService $esewaService)
    {
        $this->esewaService = $esewaService;
    }

    public function checkout(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'product_delivery_charge' => 'nullable|numeric|min:0',
        ]);

        // Prepare payment data
        $paymentData = [
            'amount' => $validated['amount'],
            'product_delivery_charge' => $validated['product_delivery_charge'] ?? 0,
            'transaction_uuid' => date('ymd-His') . '-' . Str::random(6),
        ];

        // Initialize payment through service
        $response = $this->esewaService->initiatePayment($paymentData);

        if (!$response['success']) {
            return redirect()->back()->with('error', $response['message']);
        }

        // Store transaction details in session for verification
        session([
            'esewa_transaction' => [
                'uuid' => $paymentData['transaction_uuid'],
                'amount' => $paymentData['amount'],
                'total_amount' => $paymentData['amount'] + $paymentData['product_delivery_charge'],
                'timestamp' => now(),
            ]
        ]);

        return view('payments.esewa-checkout', [
            'paymentUrl' => $response['payment_url'],
            'paymentData' => $response['payment_data'],
        ]);
    }

    public function success(Request $request)
    {
        $response = $this->esewaService->verifyPayment($request->data);

        if (!$response['success']) {
            return redirect()->route('payment.failed')->with('error', $response['message']);
        }
        // Get stored transaction details
        $storedTransaction = session('esewa_transaction');

        if (!$storedTransaction) {
            return redirect()->route('dashboard')
                ->with('error', 'Invalid transaction session');
        }

        // Verify transaction details match
        $decodedResponse = $response['data'];
        if (
            $decodedResponse['transaction_uuid'] !== $storedTransaction['uuid'] ||
            $decodedResponse['total_amount'] != $storedTransaction['total_amount']
        ) {
            return redirect()->route('dashboard')
                ->with('error', 'Transaction details mismatch');
        }

        // Clear the transaction from session
        session()->forget('esewa_transaction');

        // Update your order status here
        // You might want to dispatch a job here to handle the payment success

        return redirect()->route('payment.success')->with('success', 'Payment completed successfully');
    }

    public function failure()
    {
        // Clear the transaction from session
        session()->forget('esewa_transaction');

        return redirect()->route('payment.failed')->with('error', 'Payment failed or was cancelled');
    }

    public function checkStatus(Request $request)
    {
        $validated = $request->validate([
            'transaction_uuid' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $response = $this->esewaService->checkStatus(
            $validated['amount'],
            $validated['transaction_uuid']
        );

        return response()->json($response);
    }
}
