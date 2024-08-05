<?php

namespace Modules\Product\Controllers;

use Illuminate\Support\Facades\Blade;
use Modules\Category\Models\Category;
use Modules\Product\Components\ProductPage;

class ProductController
{
   public function product(string $categorySlug, string $productSlug)
   {
       $category = Category::query()->where('slug', $categorySlug)->first();
      if ($category) {
         $product = $category->products()->where('slug', $productSlug)->first();
         if ($product) {
            return Blade::renderComponent(new ProductPage($product));
         }
      }
      abort(404);
   }
}
