<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoService
{
    public function __construct()
    {
        $accessToken = config('services.mercadopago.key');

        if ($accessToken) {
            MercadoPagoConfig::setAccessToken($accessToken);
        }
    }

    public function isAvailable(): bool
    {
        return (bool) config('services.mercadopago.key');
    }

    public function createPreference(Order $order, $items): ?string
    {
        if (! $this->isAvailable()) {
            Log::warning('MercadoPagoService: no hay access token configurado.');

            return null;
        }

        $mpItems = [];

        foreach ($items as $item) {
            $mpItems[] = [
                'id' => (string) $item->product->id,
                'title' => $item->product->name,
                'quantity' => (int) $item->quantity,
                'unit_price' => (float) $item->product->price,
                'currency_id' => 'ARS',
            ];
        }

        $request = [
            'items' => $mpItems,
            'external_reference' => (string) $order->id,
            'back_urls' => [
                'success' => route('checkout.mp.callback', ['status' => 'success']),
                'failure' => route('checkout.mp.callback', ['status' => 'failure']),
                'pending' => route('checkout.mp.callback', ['status' => 'pending']),
            ],
            'auto_return' => 'approved',
            'statement_descriptor' => 'Marketo',
        ];

        $appUrl = config('app.url');
        if ($appUrl && ! str_contains($appUrl, 'localhost')) {
            $request['notification_url'] = route('checkout.mp.webhook');
        }

        try {
            $client = new PreferenceClient;
            $preference = $client->create($request);

            $order->update([
                'mp_preference_id' => $preference->id,
            ]);

            return $preference->init_point;
        } catch (MPApiException $e) {
            $apiResponse = $e->getApiResponse();
            $responseContent = $apiResponse ? $apiResponse->getContent() : null;

            Log::error('MercadoPagoService: error al crear preferencia.', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'status_code' => $apiResponse ? $apiResponse->getStatusCode() : null,
                'response' => $responseContent,
            ]);

            return null;
        }
    }

    public function handleCallback(Request $request, string $status): string
    {
        $preferenceId = $request->query('preference_id');
        $externalRef = $request->query('external_reference');
        $paymentId = $request->query('payment_id');

        if (! $externalRef) {
            return 'external_reference_missing';
        }

        $order = Order::find((int) $externalRef);

        if (! $order) {
            return 'order_not_found';
        }

        if ($paymentId && $this->isAvailable()) {
            $mpStatus = $this->getPaymentStatus($paymentId);

            if ($mpStatus) {
                $status = $mpStatus;
            }
        }

        $updateData = [
            'mp_preference_id' => $preferenceId ?: $order->mp_preference_id,
        ];

        if ($paymentId) {
            $updateData['mp_payment_id'] = $paymentId;
        }

        if ($status === 'success' || $status === 'approved') {
            $updateData['status'] = 'paid';
            $order->update($updateData);

            return 'paid';
        }

        if ($status === 'pending' || $status === 'in_process') {
            $updateData['status'] = 'pending';
            $order->update($updateData);

            return 'pending';
        }

        if ($status === 'failure' || $status === 'rejected' || $status === 'cancelled') {
            $updateData['status'] = 'cancelled';
            $order->update($updateData);

            return 'cancelled';
        }

        return 'unknown_status';
    }

    public function handleWebhook(Request $request): string
    {
        $data = $request->all();

        $type = $data['type'] ?? null;
        $id = $data['data']['id'] ?? null;

        if (! $type || ! $id) {
            Log::warning('MercadoPagoService: webhook recibido sin type o data.id.', ['payload' => $data]);

            return 'invalid_payload';
        }

        if ($type === 'payment') {
            return $this->processPaymentNotification((string) $id);
        }

        if ($type === 'merchant_order') {
            return $this->processMerchantOrderNotification((string) $id);
        }

        Log::info('MercadoPagoService: webhook ignorado.', ['type' => $type]);

        return 'type_ignored';
    }

    public function getPaymentStatus(string $paymentId): ?string
    {
        if (! $this->isAvailable()) {
            return null;
        }

        try {
            $client = new PaymentClient;
            $payment = $client->get((int) $paymentId);

            return match ($payment->status) {
                'approved' => 'success',
                'in_process', 'pending' => 'pending',
                'rejected', 'cancelled', 'refunded', 'charged_back' => 'failure',
                default => null,
            };
        } catch (MPApiException $e) {
            Log::error('MercadoPagoService: error al consultar pago.', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function processPaymentNotification(string $paymentId): string
    {
        $status = $this->getPaymentStatus($paymentId);

        if (! $status) {
            return 'query_failed';
        }

        try {
            $client = new PaymentClient;
            $payment = $client->get((int) $paymentId);

            $preferenceId = $payment->preference_id ?? null;
            $externalRef = $payment->external_reference ?? null;

            $order = null;

            if ($externalRef) {
                $order = Order::find((int) $externalRef);
            }

            if (! $order && $preferenceId) {
                $order = Order::where('mp_preference_id', $preferenceId)->first();
            }

            if (! $order) {
                Log::warning('MercadoPagoService: webhook - orden no encontrada.', [
                    'payment_id' => $paymentId,
                    'preference_id' => $preferenceId,
                    'external_reference' => $externalRef,
                ]);

                return 'order_not_found';
            }

            $order->update([
                'status' => match ($status) {
                    'success' => 'paid',
                    'pending' => 'pending',
                    default => 'cancelled',
                },
                'mp_payment_id' => $paymentId,
                'mp_merchant_order_id' => (string) ($payment->order?->id ?? ''),
            ]);

            return 'updated';
        } catch (MPApiException $e) {
            Log::error('MercadoPagoService: webhook - error al procesar pago.', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
            ]);

            return 'api_error';
        }
    }

    private function processMerchantOrderNotification(string $merchantOrderId): string
    {
        Log::info('MercadoPagoService: merchant_order notification recibida.', [
            'merchant_order_id' => $merchantOrderId,
        ]);

        return 'merchant_order_ignored';
    }
}
