<?php

namespace App\Services;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    private const API_URL = 'https://dolarapi.com/v1/dolares/blue';

    private const CACHE_KEY = 'dolar_blue_venta';

    private const CACHE_TTL = 3600; // 1 hora

    private const FALLBACK = 1200.0;

    /**
     * Memoización a nivel de instancia: el servicio se resuelve como singleton
     * (ver AppServiceProvider), por lo que esto evita repetir la lectura de cache
     * (una query a la tabla `cache` con CACHE_STORE=database) en cada vista/componente
     * Blade renderizado durante el mismo request — antes se repetía decenas de veces.
     */
    private ?float $resolvedRate = null;

    public function arsToUsd(float $ars): float
    {
        return round($ars / $this->getRate(), 2);
    }

    public function getRate(): float
    {
        if ($this->resolvedRate !== null) {
            return $this->resolvedRate;
        }

        try {
            return $this->resolvedRate = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
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
        } catch (QueryException) {
            Log::warning('CurrencyService: no se pudo consultar la cache (tabla no disponible).', [
                'cache_key' => self::CACHE_KEY,
            ]);

            return $this->resolvedRate = self::FALLBACK;
        }
    }
}
