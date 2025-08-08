<?php

namespace App\Http\Controllers;

use App\Models\Bilet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleXMLElement;

class PaymentController extends Controller
{
    public function success(Request $request)
    {
        $transactionId = $request->input('TransactionId');

        if (!$transactionId) {
            Log::error('Success callback: TransactionId eksik', $request->all());
            return response()->json(['message' => 'TransactionId eksik'], 400);
        }

        $queryResult = $this->queryTransactionStatus($transactionId);

        if ($queryResult['success'] && $queryResult['rc'] === '0000') {
            $tickets = Bilet::where('transaction_id', $transactionId)->get();
            foreach ($tickets as $ticket) {
                $ticket->update([
                    'status' => 'odendi',
                    'is_active' => true
                ]);
            }
            Log::info('Ödeme başarılı ve biletler aktif hale getirildi.', ['transaction_id' => $transactionId, 'query_rc' => $queryResult['rc']]);
            return response()->json(['message' => 'Ödeme başarılı. Biletler aktif hale getirildi.']);
        } else {
            $tickets = Bilet::where('transaction_id', $transactionId)->get();
            foreach ($tickets as $ticket) {
                $ticket->update(['status' => 'basarisiz']);
            }
            Log::warning('Success callback: Sorgulama API\'sinden başarılı sonuç alınamadı.', [
                'transaction_id' => $transactionId,
                'query_rc' => $queryResult['rc'] ?? 'N/A',
                'query_message' => $queryResult['message'] ?? 'N/A',
                'raw_response' => $queryResult['raw_response'] ?? 'N/A'
            ]);
            return response()->json([
                'message' => 'Ödeme durumu doğrulanamadı veya başarısız.',
                'bank_message' => $queryResult['message'] ?? 'Banka yanıtı alınamadı.'
            ], 400);
        }
    }

    public function fail(Request $request)
    {
        $transactionId = $request->input('TransactionId');
        $rc = $request->input('RC');
        $msg = $request->input('MSG');

        if (!$transactionId) {
            Log::error('Fail callback: TransactionId eksik', $request->all());
            return response()->json(['message' => 'TransactionId eksik'], 400);
        }

        $queryResult = $this->queryTransactionStatus($transactionId);

        if ($queryResult['success'] && $queryResult['rc'] === '0000') {
            $tickets = Bilet::where('transaction_id', $transactionId)->get();
            foreach ($tickets as $ticket) {
                $ticket->update([
                    'status' => 'odendi',
                    'is_active' => true
                ]);
            }
            Log::info('Fail callback: Sorgulama API\'si başarılı sonuç döndü (anlık tutarsızlık olabilir).', ['transaction_id' => $transactionId, 'query_rc' => $queryResult['rc']]);
            return response()->json(['message' => 'Ödeme aslında başarılı olarak doğrulandı. Biletler aktif hale getirildi.']);
        } else {
            $tickets = Bilet::where('transaction_id', $transactionId)->get();
            foreach ($tickets as $ticket) {
                $ticket->update(['status' => 'basarisiz']);
            }
            Log::info('Fail callback: Ödeme başarısız veya durumu doğrulanamadı.', [
                'transaction_id' => $transactionId,
                'callback_rc' => $rc,
                'callback_msg' => $msg,
                'query_rc' => $queryResult['rc'] ?? 'N/A',
                'query_message' => $queryResult['message'] ?? 'N/A',
                'raw_response' => $queryResult['raw_response'] ?? 'N/A'
            ]);
            return response()->json([
                'message' => 'Ödeme başarısız.',
                'bank_message' => $queryResult['message'] ?? $msg ?? 'Banka yanıtı alınamadı.'
            ]);
        }
    }

    protected function queryTransactionStatus(string $transactionId): array
    {
        $merchantId = env('VAKIFBANK_MERCHANT_ID');
        $password = env('VAKIFBANK_PASSWORD');
        $queryApiUrl = env('VAKIFBANK_QUERY_API_URL');

        if (!$queryApiUrl) {
            Log::error('Sorgulama API: VAKIFBANK_QUERY_API_URL tanımlı değil.');
            return ['success' => false, 'rc' => '9999', 'message' => 'Sorgulama API URL tanımlı değil.', 'raw_response' => ''];
        }

        $requestData = [
            'HostMerchantId' => $merchantId,
            'Password' => $password,
            'TransactionId' => $transactionId,
        ];

        Log::info('Sorgulama API isteği gönderiliyor:', ['transaction_id' => $transactionId, 'request_data' => $requestData]);

        try {
            $response = Http::post($queryApiUrl, $requestData);
            $rawResponseBody = $response->body();
            Log::info("raw response bilgisi :", [$rawResponseBody]);

            if (!$response->successful()) {
                Log::error('Sorgulama API HTTP hatası:', ['status' => $response->status(), 'response' => $rawResponseBody]);
                return ['success' => false, 'rc' => 'HTTP_ERROR', 'message' => 'Banka Sorgulama API\'sinden HTTP hatası alındı. Durum Kodu: ' . $response->status(), 'raw_response' => $rawResponseBody];
            }
            $decodedResponse = json_decode($rawResponseBody, true); 

            if (json_last_error() === JSON_ERROR_NONE && $decodedResponse !== null) {
                $rc = $decodedResponse['Rc'] ?? '';
                $message = $decodedResponse['Message'] ?? '';
                $authCode = $decodedResponse['AuthCode'] ?? '';

                Log::info('Sorgulama API yanıtı (JSON):', ['transaction_id' => $transactionId, 'rc' => $rc, 'message' => $message, 'raw_response' => $rawResponseBody]);

                return [
                    'success' => true,
                    'rc' => $rc,
                    'message' => $message,
                    'auth_code' => $authCode,
                    'raw_response' => $rawResponseBody
                ];
            } else {
                libxml_use_internal_errors(true);
                $xmlResponse = simplexml_load_string($rawResponseBody);
                $errors = libxml_get_errors();
                libxml_clear_errors();

                if ($xmlResponse === false) {
                    $errorMessage = 'Banka yanıtı ne JSON ne de XML olarak ayrıştırılamadı: ' . implode(', ', array_map(fn($err) => $err->message, $errors));
                    Log::error($errorMessage, ['raw_response' => $rawResponseBody]);
                    return ['success' => false, 'rc' => 'PARSE_ERROR', 'message' => $errorMessage, 'raw_response' => $rawResponseBody];
                }
                $rc = (string) ($xmlResponse->Rc ?? '');
                $message = (string) ($xmlResponse->Message ?? '');
                $authCode = (string) ($xmlResponse->AuthCode ?? '');

                Log::info('Sorgulama API yanıtı (XML):', ['transaction_id' => $transactionId, 'rc' => $rc, 'message' => $message, 'raw_response' => $rawResponseBody]);

                return [
                    'success' => true,
                    'rc' => $rc,
                    'message' => $message,
                    'auth_code' => $authCode,
                    'raw_response' => $rawResponseBody
                ];
            }
        } catch (\Exception $e) {
            Log::error('Sorgulama API çağrısında beklenmedik hata:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ['success' => false, 'rc' => 'EXCEPTION', 'message' => 'Sorgulama API çağrısı sırasında bir hata oluştu: ' . $e->getMessage(), 'raw_response' => ''];
        }
    }

    public function registerAndRedirect(Request $request)
    {
        $data = $request->validate([
            'ticket_ids' => 'required|array|min:1',
            'ticket_ids.*' => 'integer|exists:bilets,id',
        ]);

        $user = $request->user();
        if (!$user) {
            Log::error('Kullanıcı null döndü');
            return response()->json(['message' => 'Giriş yapılmamış'], 401);
        }

        $tickets = $user->bilets()->whereIn('id', $data['ticket_ids'])->get();
        if ($tickets->isEmpty()) {
            return response()->json(['message' => 'Bilet(ler) bulunamadı.'], 404);
        }

        $totalAmount = $tickets->sum('fiyat');
        if ($totalAmount <= 0) {
            return response()->json(['message' => 'Geçersiz tutar.'], 400);
        }

        $transactionId = $tickets->first()->transaction_id;

        $response = Http::post(env('VAKIFBANK_REGISTER_URL'), [
            "HostMerchantId" => env('VAKIFBANK_MERCHANT_ID'),
            "MerchantPassword" => env('VAKIFBANK_PASSWORD'),
            "HostTerminalId" => env('VAKIFBANK_TERMINAL_ID'),
            "TransactionType" => "Sale",
            "Amount" => number_format($totalAmount, 2, '.', ''),
            "AmountCode" => "949",
            "SuccessUrl" => env('VAKIFBANK_SUCCESS_URL'),
            "FailUrl" => env('VAKIFBANK_FAIL_URL'),
            "InstallmentCount" => "1",
            "TransactionId" => $transactionId,
        ]);

        $rawResponseBody = $response->body();

        if (!$response->successful()) {
            Log::error('Ödeme kaydetme API HTTP hatası:', ['status' => $response->status(), 'response' => $rawResponseBody]);
            return response()->json([
                'message' => 'Ödeme başlatılamadı. Banka ile iletişimde HTTP hatası.',
                'error' => ['status_code' => $response->status(), 'response' => $rawResponseBody]
            ], 500);
        }

        $jsonResponse = json_decode($rawResponseBody);

        if (json_last_error() === JSON_ERROR_NONE && $jsonResponse !== null) {
            $errorCode = $jsonResponse->ErrorCode ?? null;

            if (!empty($errorCode)) {
                Log::error('Ödeme başlatılamadı - Bankadan hata kodu alındı (JSON yanıtı):', [
                    'error_code' => $errorCode,
                    'raw_response' => $rawResponseBody
                ]);
                return response()->json([
                    'message' => 'Ödeme başlatılamadı.',
                    'error' => ['code' => $errorCode, 'message' => 'Banka hata kodu: ' . $errorCode]
                ], 500);
            }

            $paymentToken = $jsonResponse->PaymentToken ?? null;
            $commonPaymentUrl = $jsonResponse->CommonPaymentUrl ?? null;

            if (empty($paymentToken) || empty($commonPaymentUrl)) {
                Log::error('Ödeme başlatılamadı - Eksik PaymentToken veya Ortak Ödeme URL (JSON yanıtı):', ['raw_response' => $rawResponseBody]);
                return response()->json(['message' => 'Ödeme başlatılamadı. Banka yanıtında eksik veri.'], 500);
            }

            $redirectUrl = $commonPaymentUrl . "?Ptkn={$paymentToken}";
            return response()->json([
                'message' => 'Ödeme ekranına yönlendiriliyorsunuz.',
                'payment_url' => $redirectUrl
            ]);
        } else {
            libxml_use_internal_errors(true);
            $xmlResponse = simplexml_load_string($rawResponseBody);
            libxml_clear_errors();

            if ($xmlResponse === false) {
                $errors = libxml_get_errors();
                $errorMessage = 'Banka yanıtı ne JSON ne de XML olarak ayrıştırılamadı: ' . implode(', ', array_map(fn($err) => $err->message, $errors));
                Log::error($errorMessage, ['raw_response' => $rawResponseBody]);
                return response()->json(['message' => 'Banka yanıtı ayrıştırılamadı.', 'error' => $errorMessage], 500);
            }

            $errorCode = (string) ($xmlResponse->ErrorCode ?? '');
            if ($errorCode !== '') {
                Log::error('Ödeme başlatılamadı - Bankadan hata kodu alındı (XML yanıtı):', [
                    'error_code' => $errorCode,
                    'raw_response' => $rawResponseBody
                ]);
                return response()->json([
                    'message' => 'Ödeme başlatılamadı.',
                    'error' => ['code' => $errorCode, 'message' => 'Banka hata kodu: ' . $errorCode]
                ], 500);
            }

            $ptkn = (string) ($xmlResponse->PaymentToken ?? '');
            $commonPaymentUrl = (string) ($xmlResponse->CommonPaymentUrl ?? '');

            if (empty($ptkn) || empty($commonPaymentUrl)) {
                Log::error('Ödeme başlatılamadı - Eksik PaymentToken veya Ortak Ödeme URL (XML yanıtı):', ['raw_response' => $rawResponseBody]);
                return response()->json(['message' => 'Ödeme başlatılamadı. Banka yanıtında eksik veri.'], 500);
            }

            $redirectUrl = $commonPaymentUrl . "?Ptkn={$ptkn}";
            return response()->json([
                'message' => 'Ödeme ekranına yönlendiriliyorsunuz.',
                'payment_url' => $redirectUrl
            ]);
        }
    }
}