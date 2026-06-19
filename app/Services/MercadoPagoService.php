<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Preference\PreferenceClient;
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

    public function createPreference(Order $order, array $items): ?string
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

        try {
            $client = new PreferenceClient;
            $preference = $client->create($request);

            return $preference->init_point;
        } catch (MPApiException $e) {
            Log::error('MercadoPagoService: error al crear preferencia.', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function handleCallback(Request $request, string $status): string
    {
        $preferenceId = $request->query('preference_id');
        $externalRef = $request->query('external_reference');

        if (! $externalRef) {
            return 'external_reference_missing';
        }

        $order = Order::find((int) $externalRef);

        if (! $order) {
            return 'order_not_found';
        }

        if ($status === 'success') {
            $order->update([
                'status' => 'paid',
                'mp_preference_id' => $preferenceId,
            ]);

            return 'paid';
        }

        if ($status === 'pending') {
            $order->update([
                'status' => 'pending',
                'mp_preference_id' => $preferenceId,
            ]);

            return 'pending';
        }

        if ($status === 'failure') {
            $order->update([
                'status' => 'cancelled',
                'mp_preference_id' => $preferenceId,
            ]);

            return 'cancelled';
        }

        return 'unknown_status';
    }
}
