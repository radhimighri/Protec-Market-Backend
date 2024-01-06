<?php

namespace App\Filament\Resources\CategorieResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\{Form, Table};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\BelongsToManyRelationManager;

class ProductsRelationManager extends BelongsToManyRelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 0])->schema([
                FileUpload::make('picture')
                    ->rules(['image', 'max:1024', 'required'])
                    ->image()
                    ->label(trans('crud.products.inputs.picture'))
                    ->placeholder(trans('crud.products.inputs.picture'))
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 4,
                    ]),

                TextInput::make('name')
                    ->rules(['required', 'max:255', 'string'])
                    ->label(trans('crud.products.inputs.name'))
                    ->placeholder(trans('crud.products.inputs.name'))
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                RichEditor::make('description')
                    ->rules(['required', 'string'])
                    ->label(trans('crud.products.inputs.description'))
                    ->placeholder(trans('crud.products.inputs.description'))
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                TextInput::make('price')
                    ->rules(['required', 'numeric'])
                    ->numeric()
                    ->label(trans('crud.products.inputs.price'))
                        ->placeholder(trans('crud.products.inputs.price'))
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                TextInput::make('weight')
                    ->rules(['required', 'numeric'])
                    ->numeric()
                    ->label(trans('crud.products.inputs.weight'))
                    ->placeholder(trans('crud.products.inputs.weight'))
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),
                TextInput::make('stock')
                    ->rules(['numeric'])
                    ->label(trans('crud.products.inputs.stock'))
                    ->placeholder(trans('crud.products.inputs.stock'))
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
                Tables\Columns\ImageColumn::make('picture')->rounded()->label(trans('crud.products.inputs.picture')),
                Tables\Columns\TextColumn::make('name')->limit(50)->label(trans('crud.products.inputs.name')),
                Tables\Columns\TextColumn::make('description')->limit(50)->label(trans('crud.products.inputs.description')),
                Tables\Columns\TextColumn::make('price')->label(trans('crud.products.inputs.price')),
                Tables\Columns\TextColumn::make('weight')->label(trans('crud.products.inputs.weight')),
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
