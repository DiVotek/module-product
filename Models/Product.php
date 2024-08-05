<?php

namespace Modules\Product\Models;

use App\Traits\HasAttributes;
use App\Traits\HasBreadcrumbs;
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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Category\Models\Category;
use Modules\Filter\Models\Attribute;
use Modules\Promotions\Models\Promotion;
use Modules\Promotions\Models\Sticker;
use Modules\Seo\Traits\HasSeo;

class Product extends Model
{
    use HasFactory;
    use HasStatus;
    use HasSorting;
    use HasSeo;
    use HasSlug;
    use HasViews;
    use HasBreadcrumbs;
    use HasPromotion;
    use HasSticker;
    use HasAttributes;
    use HasReviews;
    use HasTimestamps;
    use HasTranslate;

    public const TABLE = 'products';

    protected $table = self::TABLE;

    protected $fillable = [
        'name',
        'slug',
        'sorting',
        'status',
        'sku',
        'price',
        'views',
        'images',
        'template'
    ];

    protected $casts = [
        'images' => 'array',
        'template' => 'array'
    ];

    public const GOOGLE_CONDITION_NEW = 'new';
    public const GOOGLE_CONDITION_REFURBISHED = 'refurbished';
    public const GOOGLE_CONDITION_USED = 'used';
    public const GOOGLE_CONDITION = [
        self::GOOGLE_CONDITION_NEW,
        self::GOOGLE_CONDITION_REFURBISHED,
        self::GOOGLE_CONDITION_USED,
    ];

    public const GOOGLE_ADULT_YES = 'yes';
    public const GOOGLE_ADULT_NO = 'no';
    public const GOOGLE_ADULT = [
        self::GOOGLE_ADULT_YES,
        self::GOOGLE_ADULT_NO,
    ];

    public const SLIDER_SELECT = [
        self::TABLE . '.id',
        'name',
        'slug',
        'price',
        'stock_status',
        'image',
        'parameters',
        'final_price',
        'sku'
    ];

    public const SORTING = [
        1 => 'id',
        2 => 'name asc',
        3 => 'name desc',
        4 => 'price asc',
        5 => 'price desc',
        6 => 'rating desc'
    ];

    protected string $cartTitleField = 'name';

    protected string $cartPriceField = 'price';

    public static function getDb(): string
    {
        return 'products';
    }

    public function route(): string
    {
        return tRoute('product', ['product' => $this->slug]);
    }

    public function getBreadcrumbs(): array
    {
        return [
            $this->name => $this->route(),
        ];
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function productReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_products', 'category_id', 'product_id');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'attribute_product', 'attribute_id', 'product_id');
    }

    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class, 'product_promotions', 'product_id', 'promotion_id');
    }

    public function stickers(): BelongsToMany
    {
        return $this->belongsToMany(Sticker::class, 'product_stickers', 'product_id', 'sticker_id');
    }
}
