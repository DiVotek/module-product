<?php

namespace Modules\Product\Admin\ProductResource\Widgets;

use App\Services\TableSchema;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Modules\Product\Models\Product;

class TopProducts extends BaseWidget
{
    protected static ?int $sort = 4;

    protected function getTableHeading(): string|Htmlable|null
    {
        return __('Top products');
    }

    public function table(Table $table): Table
    {
        $currentModel = Product::class;

        return $table
            ->searchable(false)
            ->query(function () use ($currentModel) {
                return $currentModel::query()->orderBy('views', 'desc')->take(5);
            })
            ->columns([
                TableSchema::getName(),
                TableSchema::getPrice(),
                TableSchema::getViews(),
            ])->actions([
                Action::make('View')
                    ->label(__('View'))
                    ->icon('heroicon-o-eye')
                    ->url(function ($record) {
                        return route('slug', ['slug' => $record->slug]);
                    }),
            ])
            ->paginated(false)->defaultPaginationPageOption(5);
    }
}
