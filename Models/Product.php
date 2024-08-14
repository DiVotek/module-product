<?php

namespace Modules\Product\Models;

use App\Traits\HasAttributes;
use App\Traits\HasBreadcrumbs;
use App\Traits\HasCategory;
use App\Traits\HasPromotion;
use App\Traits\HasReviews;
use App\Traits\HasSlug;
use App\Traits\HasSorting;
use App\Traits\HasStatus;
use App\Traits\HasSticker;
use App\Traits\HasTimestamps;
use App\Traits\HasTranslate;
use App\Traits\HasViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Seo\Traits\HasSeo;

class Product extends Model
{
    use HasAttributes;
    use HasBreadcrumbs;
    use HasCategory;
    use HasFactory;
    use HasPromotion;
    use HasReviews;
    use HasSeo;
    use HasSlug;
    use HasSorting;
    use HasStatus;
    use HasSticker;
    use HasTimestamps;
    use HasTranslate;
    use HasViews;

    protected $fillable = [
        'name',
        'slug',
        'sorting',
        'status',
        'sku',
        'price',
        'views',
        'images',
        'template',
    ];

    protected $casts = [
        'images' => 'array',
        'template' => 'array',
    ];

    public static function getDb(): string
    {
        return 'products';
    }

    public function route(): string
    {
        return tRoute('slug', ['slug' => $this->slug]);
    }

    public function getBreadcrumbs(): array
    {
        return [
            $this->name => $this->route(),
        ];
    }
}
