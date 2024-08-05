<?php

namespace Modules\Product\Admin\ProductResource\RelationManagers;

use App\Models\Language;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\Seo\Admin\SeoResource;

class SeoRelationManager extends RelationManager
{
    protected static string $relationship = 'seo';

    public function form(Form $form): Form
    {
        return SeoResource::form($form);
    }

    public function table(Table $table): Table
    {
        return SeoResource::table($table)
            ->modifyQueryUsing(function (Builder $query) {
                if (!is_multi_lang()) {
                    $query->where('language_id', main_lang_id());
                }
            })
            ->headerActions([
                Tables\Actions\CreateAction::make()->hidden(function () {
                    if (is_multi_lang()) {
                        return $this->ownerRecord->seo()->count() >= Language::query()->count();
                    }
                    return $this->ownerRecord->seo !== null;
                })
            ])->paginated(false);
    }
}
