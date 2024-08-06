<?php

namespace Modules\Product\Services;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\Setting;
use App\Models\StockStatus;
use Illuminate\Support\Carbon;

class GoogleMerchantFeedService
{
    public static function update(): string
    {
        $siteName = Setting::find(Setting::NAME)->value ?? '';
        $companyDescription = Setting::find(Setting::COMPANY_DESCRIPTION)->value ?? '';
        $siteUrl = env('APP_URL');
        $items = '';
        $products = Product::query()->get();
        $lastProduct = $products->last();

        foreach ($products as $product) {
            $productData = self::buildProductData($product);
            $items .= '<item>';

            foreach ($productData as $tag => $value) {
                $items .= "\n  <$tag>$value</$tag>";
            }

            $items .= $product->is($lastProduct) ? "\n</item>" : "\n</item>\n";
        }

        $content = <<<Blade
        <rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
        <channel>
        <title>$siteName</title>
        <link>$siteUrl</link>
        <description>$companyDescription</description>
        $items
        </channel>
        </rss>
        Blade;

        //        if (Storage::disk('docs')->exists('google-merchant-feed.xml'))
        //            Storage::disk('docs')->delete('google-merchant-feed.xml');
        //        Storage::disk('docs')->put('google-merchant-feed.xml', $content);
        return $content;
    }

    private static function buildProductData(mixed $product): array
    {
        $currency = app('currency')->code;
        $country = Setting::find(Setting::COUNTRY)->value ?? '';

        $data = [
            'g:id' => $product->id,
            'g:title' => $product->name,
            'g:description' => $product->description,
            'g:link' => asset($product->slug),
            'g:image_link' => isset($product->image[0]) ? asset('storage' . $product->image[0]) : '',
            'g:condition' => 'new',
            'g:targetCountry' => $country,
            'g:adult' => 'no',
            'g:availability' => 'in stock',
            'g:availability_date' => Carbon::parse($product->created_at)->addYear()->format('Y-m-d\TH:iO'),
            'g:price' => "{$product->price} {$currency}",
            'g:brand' => name(),
        ];

        return $data;
    }
}
