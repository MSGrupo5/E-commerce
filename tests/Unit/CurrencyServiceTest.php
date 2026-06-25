<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\CurrencyService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CurrencyServiceTest extends TestCase
{
    public function test_get_rate_memoizes_within_the_same_instance(): void
    {
        Cache::shouldReceive('remember')
            ->once()
            ->andReturn(1234.5);

        $service = new CurrencyService;

        $this->assertSame(1234.5, $service->getRate());
        // Segunda llamada: no debe volver a consultar la cache (evita N queries
        // a la tabla `cache` cuando el servicio se resuelve como singleton y el
        // View::composer global lo invoca en cada vista/componente renderizado).
        $this->assertSame(1234.5, $service->getRate());
    }

    public function test_ars_to_usd_uses_the_memoized_rate(): void
    {
        Cache::shouldReceive('remember')
            ->once()
            ->andReturn(1000.0);

        $service = new CurrencyService;

        $this->assertSame(10.0, $service->arsToUsd(10000));
        $this->assertSame(20.0, $service->arsToUsd(20000));
    }

    public function test_currency_service_is_resolved_as_a_singleton(): void
    {
        $this->assertSame(
            app(CurrencyService::class),
            app(CurrencyService::class)
        );
    }
}
