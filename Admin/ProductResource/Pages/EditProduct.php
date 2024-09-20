<?php

namespace Modules\Product\Admin\ProductResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Admin\ProductResource;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected function getHeaderActions(): array
    {
        return [
            // $this->getSaveFormAction()
            //     ->formId('form'),
            DeleteAction::make(),
            ViewAction::make()->url(fn($record) => $record->route())->openUrlInNewTab(true),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record = parent::handleRecordUpdate($record, $data);
        if ($record->category_id && !$record->categories()->where('category_id', $record->category_id)->exists()) {
            $record->categories()->attach($record->category_id);
        }
        return $record;
    }
}
