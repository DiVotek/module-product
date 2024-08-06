<?php

namespace Modules\Product\Models;

use App\Traits\HasStatus;
use App\Traits\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    use HasStatus;
    use HasTimestamps;

    protected $fillable = ['name', 'code', 'number', 'rate', 'status'];

    public static function getDb(): string
    {
        return 'currencies';
    }
}
