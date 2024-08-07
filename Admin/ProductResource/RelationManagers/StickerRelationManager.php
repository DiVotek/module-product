<?php

namespace Modules\Product\Admin\ProductResource\RelationManagers;

use App\Services\Schema;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StickerRelationManager extends RelationManager
{
    protected static string $relationship = 'stickers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
//                Schema::getStickers()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->reorderable('product_stickers.sorting')
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->orderBy('name'))
                    ->recordSelectSearchColumns(['name'])
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
