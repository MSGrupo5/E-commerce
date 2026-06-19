<?php

declare(strict_types=1);

namespace App\Providers;

use App\Listeners\MergeGuestCart;
use App\Models\Product;
use App\Policies\ProductPolicy;
use App\Services\CurrencyService;
use Illuminate\Auth\Events\Login;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::policy(Product::class, ProductPolicy::class);
        Event::listen(Login::class, MergeGuestCart::class);

        View::composer('*', function (\Illuminate\View\View $view) {
            $view->with('usdToArs', app(CurrencyService::class)->getRate());
        });

        Paginator::defaultView('vendor.pagination.marketo');
    }
}
