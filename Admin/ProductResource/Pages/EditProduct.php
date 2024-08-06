<?php

namespace Modules\Product\Admin\ProductResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Product\Admin\ProductResource;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
}
