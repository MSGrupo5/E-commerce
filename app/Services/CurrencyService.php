<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    private const API_URL  = 'https://dolarapi.com/v1/dolares/blue';
    private const CACHE_KEY = 'dolar_blue_venta';
    private const CACHE_TTL = 3600; // 1 hora
    private const FALLBACK  = 1200.0;

    public function arsToUsd(float $ars): float
    {
        return round($ars / $this->getRate(), 2);
    }

    public function getRate(): float
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            try {
                $response = Http::timeout(5)->get(self::API_URL);

                if ($response->successful() && $response->json('venta')) {
                    return (float) $response->json('venta');
                }
            } catch (\Throwable $e) {
                Log::warning('CurrencyService: no se pudo obtener la cotización del dólar.', [
                    'error' => $e->getMessage(),
                ]);
            }

            return self::FALLBACK;
        });
    }
}
