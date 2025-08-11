<?php

namespace App\Http\BusinessLogic\Methods\Classes\Banking;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PSBBankService
{
    protected string $merchantId;
    protected string $secretKey;
    protected bool $testMode;
    protected string $baseUrl;
    protected string $baseUrlPay;

    public function __construct()
    {
        $this->merchantId = config('psb.merchant_id', env('PSB_MERCHANT_ID'));
        $this->secretKey = config('psb.secret_key', env('PSB_SECRET_KEY'));
        $this->testMode  = (bool) env('PSB_TEST_MODE', true);

        $this->baseUrl    = $this->testMode
            ? 'https://oosdemo.pscb.ru/merchantApi'
            : 'https://oos.pscb.ru/merchantApi';

        $this->baseUrlPay = $this->testMode
            ? 'https://oosdemo.pscb.ru/pay'
            : 'https://oos.pscb.ru/pay';
    }

    /** Подпись */
    protected function createSignature(array $data): string
    {
        return hash('sha256', json_encode($data, JSON_UNESCAPED_UNICODE) . $this->secretKey);
    }

    /** Универсальный POST-запрос
     * @throws \Exception
     */
    protected function sendRequest(string $endpoint, array $payload, bool $signHeader = true): array
    {
        $url = Str::startsWith($endpoint, 'http') ? $endpoint : "{$this->baseUrl}/{$endpoint}";

        $headers = [];
        if ($signHeader) {
            $headers['Signature'] = $this->createSignature($payload);
        }

        $response = Http::withHeaders($headers)->post($url, $payload);

        if ($response->failed()) {
            throw new \Exception("HTTP Error: {$response->status()}");
        }

        $result = $response->json();

        if (!isset($result['status'])) {
            throw new \Exception("Invalid API response");
        }

        if ($result['status'] === 'STATUS_FAILURE') {
            throw new \Exception($result['errorDescription'] ?? 'Unknown error', 400);
        }

        return $result;
    }

    /** 1. Создание платежа */
    public function createPayment(array $message): array
    {
        $nonce = sha1(time() . Str::random());
        $message['nonce'] = $nonce;
        $message['orderId'] = $message['orderId'] ?? 'order-' . time();

        $payload = [
            'marketPlace' => $this->merchantId,
            'message'     => base64_encode(json_encode($message, JSON_UNESCAPED_UNICODE)),
            'signature'   => $this->createSignature($message),
        ];

        return [
            'url' => $this->baseUrlPay,
            'params' => $payload,
        ];
    }

    /** 2. Callback расшифровка */
    public function decryptCallback(string $encrypted): array
    {
        $key = md5($this->secretKey, true);
        $decrypted = openssl_decrypt(
            $encrypted,
            'AES-128-ECB',
            $key,
            OPENSSL_RAW_DATA
        );

        return json_decode($decrypted, true);
    }

    /** 3. Создание счета
     * @throws \Exception
     */
    public function createInvoice(array $data): array
    {
        $data['marketPlace'] = $this->merchantId;
        return $this->sendRequest('createInvoice', $data);
    }

    /** 4. Отмена счета
     * @throws \Exception
     */
    public function cancelInvoice(string $orderId): array
    {
        return $this->sendRequest('cancelInvoice', [
            'marketPlace' => $this->merchantId,
            'orderId'     => $orderId,
        ]);
    }

    /** 5. Возврат платежа
     * @throws \Exception
     */
    public function refundPayment(array $data): array
    {
        $data['marketPlace'] = $this->merchantId;
        return $this->sendRequest('refundPayment', $data);
    }

    /** 6. Создание рекуррентного QR
     * @throws \Exception
     */
    public function createQrCode(array $data): array
    {
        $data['marketPlace'] = $this->merchantId;
        return $this->sendRequest('createQrCode', $data);
    }

    /** 7. Рекуррентный платеж
     * @throws \Exception
     */
    public function payRecurrent(array $data): array
    {
        $data['marketPlace'] = $this->merchantId;
        return $this->sendRequest('payRecurrent', $data);
    }

    /** 8. Отмена рекуррентов
     * @throws \Exception
     */
    public function cancelRecurrent(array $data): array
    {
        $data['marketPlace'] = $this->merchantId;
        return $this->sendRequest('cancelRecurrent', $data);
    }

    /** 9. Проверка платежа
     * @throws \Exception
     */
    public function checkPayment(array $data): array
    {
        $data['marketPlace'] = $this->merchantId;
        return $this->sendRequest('checkPayment', $data);
    }

    /** 10. Получение списка платежей
     * @throws \Exception
     */
    public function getPayments(array $data): array
    {
        $data['marketPlace'] = $this->merchantId;
        return $this->sendRequest('getPayments', $data);
    }
}
