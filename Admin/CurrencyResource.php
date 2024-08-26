<?php

namespace Modules\Product\Admin;

use App\Models\Setting;
use App\Services\Schema;
use App\Services\TableSchema;
use Filament\Forms\Components\Section as ComponentsSection;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Admin\CurrencyResource\Pages\ManageCurrencies;
use Modules\Product\Models\Currency;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    public static function getNavigationGroup(): ?string
    {
        return __('System');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getModelLabel(): string
    {
        return __('Currency');
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

    public static function getPluralModelLabel(): string
    {
        return __('Currencies');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ComponentsSection::make()->schema([
                    Schema::getName(),
                    Schema::getStatus(),
                    Schema::getRate(),
                ])
            ]);
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->translateLabel(),
                TableSchema::getStatus(),
                TextInputColumn::make('name')->translateLabel()->rules(['required|string']),
                TextInputColumn::make('rate')->translateLabel()->rules(['required', 'numeric']),
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Settings')
                    ->slideOver()
                    ->icon('heroicon-o-cog')
                    ->modal()
                    ->fillForm(function (): array {
                        return [
                            'main_currency' => setting(config('settings.main_currency')),
                            'front_currency' => setting(config('settings.front_currency')),
                        ];
                    })
                    ->action(function (array $data): void {
                        setting([
                            config('settings.main_currency') => $data['main_currency'],
                            config('settings.front_currency') => $data['front_currency'],
                        ]);
                        Setting::updatedSettings();
                    })
                    ->form(function ($form) {
                        $currencies = Currency::query()->pluck('name', 'id')->toArray();
                        return $form
                            ->schema([
                                ComponentsSection::make('')->schema([
                                    Select::make('main_currency')->options($currencies)->native(false)->required(),
                                    Select::make('front_currency')->options($currencies)->native(false)->required(),
                                ]),
                            ]);
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCurrencies::route('/'),
        ];
    }
}
