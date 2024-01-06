<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\{Form, Table};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Resources\RelationManagers\HasManyRelationManager;

class ReviewsRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $recordTitleAttribute = 'id';

    protected function getResourceTable(): Table
    {
        $table = Table::make();

        $table->actions([
            $this->getViewAction(),
            $this->getEditAction(),
            $this->getDissociateAction(),
            $this->getDeleteAction(),
        ]);

        $table->bulkActions(array_merge(
            ($this->canDeleteAny() ? [$this->getDeleteBulkAction()] : []),
            ($this->canDissociateAny() ? [$this->getDissociateBulkAction()] : []),
        ));

        $table->headerActions(array_merge(
            //($this->canCreate() ? [$this->getCreateAction()] : []),
            ($this->canAssociate() ? [$this->getAssociateAction()] : []),
        ));

        return $this->table($table);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 0])->schema([
                RichEditor::make('review')
                    ->rules(['required', 'max:255', 'string'])
                    ->label(trans('crud.reviews.inputs.review'))
                    ->placeholder('Review')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('review')->limit(50)->label(trans('crud.reviews.inputs.review')),
                Tables\Columns\TextColumn::make('product.name')->limit(50)->label(trans('crud.products.name')),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label(trans('crud.filters.created_from')),
                        Forms\Components\DatePicker::make('created_until')->label(trans('crud.filters.created_until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(
                                    Builder $query,
                                    $date
                                ): Builder => $query->whereDate(
                                    'created_at',
                                    '>=',
                                    $date
                                )
                            )
                            ->when(
                                $data['created_until'],
                                fn(
                                    Builder $query,
                                    $date
                                ): Builder => $query->whereDate(
                                    'created_at',
                                    '<=',
                                    $date
                                )
                            );
                    }),

                
            ]);
    }
}
