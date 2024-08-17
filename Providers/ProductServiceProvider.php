<?php

namespace Modules\Product\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Product\Models\Currency;

class ProductServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Product';

    public function boot(): void
    {
        app()->bind('currency', function () {
            $currency = setting(config('settings.main_currency'),0);
            if ($currency <= 0) {
                $currency = Currency::first()->id ?? 0;
            }
            $currency = Currency::find($currency);

            return $currency;
        });
        $this->mergeConfigFrom(
            module_path('Product', 'config/settings.php'),
            'settings'
        );
        $this->loadMigrations();
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'product');
    }

    public function register(): void {}

    private function loadMigrations(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Migrations'));
    }
}
