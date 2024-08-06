<?php

namespace Modules\Product\Controllers;

use Illuminate\Support\Facades\Blade;
use Modules\Product\Components\ProductPage;
use Modules\Product\Models\Product;

class ProductController
{
   public function product(string $productSlug)
   {
       $product = Product::query()->where('slug', $productSlug)->first();
       if ($product) {
           return Blade::renderComponent(new ProductPage($product));
       }
      abort(404);
   }
}
