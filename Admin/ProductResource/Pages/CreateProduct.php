<?php

namespace Modules\Product\Admin\ProductResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Admin\ProductResource;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = parent::handleRecordCreation($data);
        if ($record->category_id && !$record->categories()->where('category_id', $record->category_id)->exists()) {
            $record->categories()->attach($record->category_id);
        }
        return $record;
    }
}
