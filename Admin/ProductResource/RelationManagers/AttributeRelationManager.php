<?php

namespace Modules\Product\Admin\ProductResource\RelationManagers;

use App\Models\Language;
use App\Services\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Filter\Models\Attribute;

class AttributeRelationManager extends RelationManager
{
    protected static string $relationship = 'attributes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        $language = Hidden::make('language_id');
        if (is_multi_lang()) {
            $language = Schema::getSelect('language_id', Language::query()->pluck('name', 'id')->toArray() ?? []);
        }
        $language = $language->default(main_lang_id());

        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('value'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->orderBy('name'))
                    ->recordSelectSearchColumns(['name'])
                    ->form([
                        $language,
                        Schema::getSelect('attributes', Attribute::query()
                            ->pluck('name', 'id')
                            ->toArray() ?? []
                        ),
                        TextInput::make('value')
                            ->rules('string', 'max:255')
                            ->translatable()
                    ])
                    ->action(function ($data) {
                        $product = $this->getOwnerRecord();

                        if (!$product) {
                            throw new \Exception('Product is null');
                        }

                        DB::table('attribute_products')->insert([
                            'language_id' => $data['language_id'],
                            'attribute_id' => $data['attributes'],
                            'product_id' => $product->id,
                            'value' => $data['value'],
                        ]);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('Edit'))
                    ->modalHeading(__('Edit Attribute'))
                    ->modalWidth('lg')
                    ->form([
                        $language,
                        TextInput::make('value')
                            ->rules('string', 'max:255')
                            ->translatable()
                    ])
                    ->action(function ($record, $data) {
                        DB::table('attribute_products')
                            ->where('product_id', $record->pivot->product_id)
                            ->where('attribute_id', $record->pivot->attribute_id)
                            ->update([
                                'language_id' => $data['language_id'],
                                'value' => $data['value'],
                            ]);
                    }),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
