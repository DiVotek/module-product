<?php

namespace Modules\Product\Components\Microdata;

use App\View\Components\Microdata;
use Closure;
use Illuminate\Contracts\View\View;
use Modules\Product\Models\ProductReview;

class Product extends Microdata
{
    public function __construct(\Modules\Product\Models\Product $entity)
    {
        $properties = $this->buildData($entity);
        parent::__construct('Product', $properties);
    }

    public function render(): View|Closure|string
    {
        return '<x-microdata :type="$type" :properties="$properties" />';
    }

    public function buildData(\Modules\Product\Models\Product $entity): array
    {
        $currency = app('currency')->code;
        $product = \Modules\Product\Models\Product::find($entity->id);
        $reviews = ProductReview::where('product_id', $entity->id)->get();
        $rating = $reviews->avg('rating');
        $stockStatus = 'http://schema.org/InStock';

        $data = [
            'name' => $entity->name,
            'image' => asset('/storage/' . $entity->image[0] ?? ''),
            'description' => $entity->meta_description,
            'sku' => $entity->sku ?? '',
            'brand' => (object) [
                '@type' => 'Brand',
                'name' => app('company_name') ?? '',
            ],
            'review' => (object) [
                '@type' => 'Review',
                'reviewRating' => (object) [
                    '@type' => 'Rating',
                    'ratingValue' => $rating,
                    'bestRating' => 5,
                ],
                'author' => (object) [
                    '@type' => 'Person',
                    'name' => app('company_name') ?? ''
                ],
            ],
            'aggregateRating' => (object) [
                '@type' => 'AggregateRating',
                'ratingValue' => $rating,
                'reviewCount' => $reviews->count()
            ],
            'offers' => (object) [
                '@type' => 'Offer',
                'url' => request()->url() . '/' . $entity->slug,
                'priceCurrency' => $currency,
                'price' => $entity->price,
                'priceValidUntil' => $product->updated_at->format('Y-m-d'),
                'itemCondition' => 'http://schema.org/NewCondition',
                'availability' => $stockStatus,
                'seller' => (object) [
                    '@type' => 'Organization',
                    'name' => app('company_name') ?? '',
                ],
            ],
        ];

        return $data;
    }
}
