<?php

use App\Actions\GetMerchantFeed;
use App\Models\StaticPage;
use App\Models\SystemPage;
use Illuminate\Support\Facades\Route;
use Modules\Product\Controllers\ProductController;

function product_slug()
{
    $productPage = StaticPage::query()->where('id', SystemPage::query()->where('name', 'Product')->first()->page_id ?? 0)->first();

    return $productPage && $productPage->slug ? $productPage->slug : 'product';
}
Route::get(product_slug() . '/{product}', [ProductController::class, 'product'])->name('product');
Route::get('google-merchant-feed', [GetMerchantFeed::class, 'handle']);
