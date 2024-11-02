<?php
// app/Http/Controllers/EsewaController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EsewaController extends Controller
{
    public function success(Request $request)
    {
        // Log the successful test payment
        Log::info('eSewa Test Payment Success', $request->all());

        return redirect()->route('dashboard')
            ->with('success', 'Test payment completed successfully! Reference ID: ' . $request->refId);
    }

    public function failure(Request $request)
    {
        // Log the failed test payment
        Log::info('eSewa Test Payment Failed', $request->all());

        return redirect()->route('dashboard')
            ->with('error', 'Test payment failed! Please try again.');
    }
}
