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
            'tax_amount' => 'nullable|numeric|min:0',
            'product_service_charge' => 'nullable|numeric|min:0',
            'product_delivery_charge' => 'nullable|numeric|min:0',
        ]);

        // Prepare payment data
        $paymentData = [
            'amount' => $validated['amount'],
            'tax_amount' => $validated['tax_amount'] ?? 0,
            'product_service_charge' => $validated['product_service_charge'] ?? 0,
            'product_delivery_charge' => $validated['product_delivery_charge'] ?? 0,
            'transaction_uuid' => date('ymd-His') . '-' . Str::random(6),
        ];

        // Initialize payment through service
        $response = $this->esewaService->initiatePayment($paymentData);

        if (!$response['success']) {
            return redirect()->back()->with('error', $response['message']);
        }

        return view('payments.esewa-checkout', [
            'paymentUrl' => $response['payment_url'],
            'paymentData' => $response['payment_data'],
        ]);
    }

    public function success(Request $request)
    {
        $response = $this->esewaService->verifyPayment($request->data);

        if (!$response['success']) {
            return redirect()->route('esewa.failure')->with('error', $response['message']);
        }

        // Update your order status here
        // You might want to dispatch a job here to handle the payment success

        $successMessage = $request->session()->get('success', 'Payment completed successfully');
        return view('payments.esewa-success', ['message' => $successMessage]);
    }

    public function failure(Request $request)
    {
        $errorMessage = $request->session()->get('error', 'Payment failed or was cancelled');
        return view('payments.esewa-failure', ['error' => $errorMessage]);
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
