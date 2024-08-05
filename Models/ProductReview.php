<?php

namespace Modules\Product\Models;

use App\Traits\HasStatus;
use App\Traits\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    use HasFactory;
    use HasStatus;
    use HasTimestamps;

    public const TABLE = 'product_reviews';

    protected $table = self::TABLE;

    protected $fillable = [
        'name',
        'product_id',
        'rating',
        'image',
        'comment',
        'status'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function getDb(): string
    {
        return 'product_reviews';
    }
}
