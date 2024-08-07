<?php

namespace Modules\Product\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Modules\Seo\Models\Seo;

class GoogleMerchantFeedService
{
    public static function update(): string
    {
        $siteName = setting(config('settings.company_name'), '');
        $companyDescription = setting(config('settings.company_description'), '');
        $siteUrl = url('/');
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
        $currency = app('currency')->code ?? '';
        $country = setting(config('settings.country'), '');

        $data = [
            'g:id' => $product->id,
            'g:title' => $product->name,
            'g:description' => DB::table(Seo::getDb())
                ->where('language_id', main_lang_id())
                ->where('seoable_id', $product->id)
                ->where('seoable_type', 'Modules\Product\Models\Product')
                ->first()
                    ->description ?? '',
            'g:link' => asset($product->slug),
            'g:image_link' => isset($product->images[0]) ? asset('storage' . $product->images[0]) : '',
            'g:condition' => 'new',
            'g:targetCountry' => $country,
            'g:adult' => 'no',
            'g:availability' => 'in stock',
            'g:availability_date' => Carbon::parse($product->created_at)->addYear()->format('Y-m-d\TH:iO'),
            'g:price' => "{$product->price} {$currency}",
            'g:brand' => company_name(),
        ];

        return $data;
    }
}
