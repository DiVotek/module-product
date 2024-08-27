<?php

namespace Modules\Product\Admin\ProductResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
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

    // protected function getFooterWidgets(): array
    // {
    //     return [];
    // }
}
