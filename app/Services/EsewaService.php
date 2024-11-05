<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class EsewaService
{
    private $baseUrl;
    private $secretKey;

    public function __construct()
    {
        $this->baseUrl = 'https://rc-epay.esewa.com.np';
        $this->secretKey = config('services.esewa.secret_key');
    }

    private function generateSignature($totalAmount, $transactionUuid)
    {
        $dataToSign = "total_amount={$totalAmount},transaction_uuid={$transactionUuid},product_code=" . config('services.esewa.product_code');
        return base64_encode(hash_hmac('sha256', $dataToSign, $this->secretKey, true));
    }

    private function validatePaymentData($data)
    {
        $requiredFields = ['amount', 'transaction_uuid'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Missing required field: {$field}");
            }
        }
    }

    public function initiatePayment($data)
    {
        try {
            //validate
            $this->validatePaymentData($data);

            $totalAmount = $data['amount'] +
                ($data['tax_amount'] ?? 0) +
                ($data['product_service_charge'] ?? 0) +
                ($data['product_delivery_charge'] ?? 0);

            $paymentData = [
                'amount' => $data['amount'],
                'tax_amount' => $data['tax_amount'] ?? 0,
                'product_service_charge' => $data['product_service_charge'] ?? 0,
                'product_delivery_charge' => $data['product_delivery_charge'] ?? 0,
                'total_amount' => $totalAmount,
                'transaction_uuid' => $data['transaction_uuid'],
                'product_code' => config('services.esewa.product_code'),
                'success_url' => route('esewa.success'),
                'failure_url' => route('esewa.failure'),
                'signed_field_names' => 'total_amount,transaction_uuid,product_code',
                'signature' => $this->generateSignature($totalAmount, $data['transaction_uuid'])
            ];

            return [
                'success' => true,
                'payment_url' => $this->baseUrl . '/api/epay/main/v2/form',
                'payment_data' => $paymentData
            ];
        } catch (Exception $e) {
            Log::error('eSewa Payment Initiation Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function verifyPayment($encodedResponse)
    {
        try {
            //decode the response
            $decodedResponse = json_decode(base64_decode($encodedResponse, true), true);
            //verify signature
            if (!$decodedResponse) {
                throw new Exception('Invalid response format');
            }
            //$dataToSign = "transaction_code={$decodedResponse['transaction_code']},status={$decodedResponse['status']},total_amount={$decodedResponse['total_amount']},transaction_uuid={$decodedResponse['transaction_uuid']},product_code={$decodedResponse['product_code']},signed_field_names={$decodedResponse['signed_field_names']}";
            $signedFieldNames = explode(',', $decodedResponse['signed_field_names']);
            $dataToSignParts = [];
            foreach ($signedFieldNames as $fieldName) {
                $dataToSignParts[] = "{$fieldName}={$decodedResponse[$fieldName]}";
            }
            $dataToSign = implode(',', $dataToSignParts);
            $generatedSignature = base64_encode(hash_hmac('sha256', $dataToSign, $this->secretKey, true));
            if ($generatedSignature !== $decodedResponse['signature']) {
                throw new Exception('Invalid Signature');
            }
            return [
                'success' => true,
                'data' => $decodedResponse
            ];
        } catch (Exception $e) {
            Log::error('eSewa Payment Verification Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function checkStatus($totalAmount, $transactionUuid)
    {
        try {
            $response = Http::get($this->baseUrl . '/api/epay/transaction/status/', [
                'product_code' => config('services.esewa.product_code'),
                'total_amount' => $totalAmount,
                'transaction_uuid' => $transactionUuid,
            ]);

            if (!$response->successful()) {
                throw new Exception('Status Check Failed');
            }

            return [
                'success' => true,
                'data' => $response->json()
            ];
        } catch (Exception $e) {
            Log::error('eSewa Status Check Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
