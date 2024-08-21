<?php

namespace Modules\Product\Models;

use App\Traits\HasAttributes;
use App\Traits\HasBreadcrumbs;
use App\Traits\HasCategory;
use App\Traits\HasImages;
use App\Traits\HasPromotion;
use App\Traits\HasReviews;
use App\Traits\HasSlug;
use App\Traits\HasSorting;
use App\Traits\HasStatus;
use App\Traits\HasSticker;
use App\Traits\HasTags;
use App\Traits\HasTemplate;
use App\Traits\HasTimestamps;
use App\Traits\HasTranslate;
use App\Traits\HasViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Filter\Models\Attribute;
use Modules\Seo\Traits\HasSeo;
use Nwidart\Modules\Facades\Module;

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
    use HasImages;
    use HasTimestamps;
    use HasTranslate;
    use HasTemplate;
    use HasViews;
    use HasTags;

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

    public function attributes()
    {
        if (Module::find('Promotions') && Module::find('Promotions')->isEnabled()) {
            return $this->belongsToMany(Attribute::class, 'attribute_products', 'product_id', 'attribute_id')->withPivot('language_id', 'value');
        }
    }

    public function optionValues()
    {
        if (module_enabled('Options')) {
            return $this->belongsToMany(\Modules\Options\Models\OptionValue::class, 'product_option')
                ->withPivot('sign', 'price');
        }
    }
    public function options()
    {
        if (module_enabled('Options')) {
            return $this->hasManyThrough(\Modules\Options\Models\Option::class, \Modules\Options\Models\OptionValue::class, 'id', 'id', 'id', 'option_id')
            ->distinct();
        }
    }
}
