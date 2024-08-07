<?php

namespace Modules\Product\Admin\ProductResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Product\Admin\ProductResource;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}
