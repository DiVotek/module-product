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
use App\Traits\HasTeam;
use App\Traits\HasTemplate;
use App\Traits\HasTimestamps;
use App\Traits\HasTranslate;
use App\Traits\HasViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Addons\Models\Addon;
use Modules\Contractor\Models\Contractor;
use Modules\Filter\Models\Attribute;
use Modules\Manufacturer\Models\Manufacturer;
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
    use HasTeam;

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
        'measure',
        'measure_quantity',
        'category_id',
        'manufacturer_id',
        'category_id',
        'custom',
        'contractor_id',
    ];

    protected $casts = [
        'images' => 'array',
        'template' => 'array',
        'custom' => 'array',
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
        $breadcrumbs = [];
        if(module_enabled('Category') && $this->category()->exists()) {
            $breadcrumbs = $this->category->getBreadcrumbs();
        }
        $breadcrumbs[$this->name] = $this->route();
        return $breadcrumbs;
    }


    public function attributes()
    {
        if (module_enabled('Filter')) {
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

    public function contractor()
    {
        return Module::find('Contractor') && Module::find('Contractor')->isEnabled()
            ? $this->belongsTo(Contractor::class, 'contractor_id')
            : null;
    }

    public function manufacturer()
    {
        return Module::find('Manufacturer') && Module::find('Manufacturer')->isEnabled()
            ? $this->belongsTo(Manufacturer::class, 'manufacturer_id')
            : null;
    }

    public function addons()
    {
        if (Module::find('Addons') && Module::find('Addons')->isEnabled()) {
            return $this->belongsToMany(Addon::class, 'addon_products', 'addon_id', 'product_id')->withPivot('price');
        }
    }
}
