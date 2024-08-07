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
            $currency = (int) Setting::find(Setting::MAIN_CURRENCY)->value;
            if ($currency <= 0) {
                $currency = Currency::first()->id ?? 0;
            }
            $currency = Currency::find($currency);

            return $currency;
        });
        $this->loadMigrations();
        Route::middleware('web')->group(module_path('Product', 'routes/web.php'));
    }

    public function register(): void {}

    private function loadMigrations(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Migrations'));
    }
}
