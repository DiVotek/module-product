<?php

namespace Modules\Product\Admin\ProductResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Product\Admin\ProductResource;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->formId('form'),
                DeleteAction::make()
        ];
    }

    // protected function getFooterWidgets(): array
    // {
    //     return [];
    // }
}
