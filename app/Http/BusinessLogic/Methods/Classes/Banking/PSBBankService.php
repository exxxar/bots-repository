<?php

namespace App\Http\BusinessLogic\Methods\Classes\Banking;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PSBBankService
{
    protected string $baseUrl;
    protected string $token;
    protected string $merchantId;

    public function __construct()
    {
        $this->baseUrl = config('psb.base_url');       // Например: https://dev-api.psbank.ru/
        $this->token = config('psb.api_token');        // Токен авторизации
        $this->merchantId = config('psb.merchant_id'); // ID мерчанта
    }

    /**
     * Формирование ссылки на оплату через СБП
     */
    public function createSbpPaymentLink(float $amount, string $orderId, string $description): ?string
    {
        $endpoint = $this->baseUrl . '/sbp/v1/payment-link'; // примерный путь, зависит от API PSB

        $response = Http::withToken($this->token)
            ->post($endpoint, [
                'amount' => $amount * 100, // в копейках
                'order_id' => $orderId,
                'description' => $description,
                'merchant_id' => $this->merchantId,
                'callback_url' => route('psb.callback'),
            ]);

        if ($response->successful()) {
            return $response->json('payment_url');
        }

        Log::error('Ошибка при создании ссылки СБП ПСБ', [
            'response' => $response->body()
        ]);

        return null;
    }

    /**
     * Обработка обратного ответа от ПСБ
     */
    public function handleCallback(array $data): bool
    {
        Log::info('PSB Callback', $data);

        // Пример простой валидации (нужна доработка под документацию банка)
        if (!isset($data['order_id'], $data['status'])) {
            return false;
        }

        if ($data['status'] === 'success') {
            // здесь можно обновить статус заказа, начислить товар и т.д.
            $order = Order::where('order_id', $data['order_id'])->first();

            if ($order) {
                $order->update(['status' => 'paid']);
            }

            return true;
        }

        return false;
    }
}
