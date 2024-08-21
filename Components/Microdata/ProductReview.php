<?php

namespace Modules\Product\Components\Microdata;

use App\View\Components\Microdata;
use Closure;
use Illuminate\Contracts\View\View;

class ProductReview extends Microdata
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
        $currency = setting('currency')->code;
        $product = \Modules\Product\Models\Product::query()->find($entity->id);
        $reviews = \Modules\Product\Models\ProductReview::query()->where('product_id', $entity->id)->get();
        $rating = $reviews->avg('rating');
        $brand = setting('company_name');
        $stockStatus = 'http://schema.org/InStock';

        $data = [
            'name' => $entity->name,
            'image' => asset('/storage/' . $entity->image[0] ?? ''),
            'description' => $entity->meta_description,
            'sku' => $entity->sku ?? '',
            'brand' => (object) [
                '@type' => 'Brand',
                'name' => $brand,
            ],
            'aggregateRating' => (object) [
                '@type' => 'AggregateRating',
                'ratingValue' => $rating,
                'reviewCount' => $reviews->count()
            ],
        ];

        foreach ($reviews as $review) {
            $data['review'][] = (object) [
                '@type' => 'Review',
                'author' => (object) [
                    '@type' => 'Person',
                    'name' => $review->name
                ],
                'datePublished' => $review->created_at->format('Y-m-d'),
                'reviewBody' => $review->message,
                'reviewRating' => (object) [
                    '@type' => 'Rating',
                    'ratingValue' => $rating,
                    'bestRating' => 5,
                    'worstRating' => 1
                ],
            ];
        }

        $data['offers'] = (object) [
            '@type' => 'Offer',
            'url' => \Modules\Product\Models\Product::route(),
            'priceCurrency' => $currency,
            'price' => $entity->price,
            'priceValidUntil' => $product->updated_at->format('Y-m-d'),
            'itemCondition' => 'http://schema.org/NewCondition',
            'availability' => $stockStatus,
            'seller' => (object) [
                '@type' => 'Organization',
                'name' => setting('company_name') ?? '',
            ],
        ];

        return $data;
    }
}
