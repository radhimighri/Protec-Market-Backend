<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\{Form, Table};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\HasManyRelationManager;

class OrdersRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $recordTitleAttribute = 'number';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 0])->schema([
                TextInput::make('number')
                    ->rules(['required', 'max:255', 'string'])
                    ->unique('orders', 'number', fn(?Model $record) => $record)
                    ->label(trans('crud.orders.inputs.number'))
                    ->placeholder(trans('crud.orders.inputs.number'))
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                TextInput::make('total_price')
                    ->rules(['nullable', 'numeric'])
                    ->numeric()
                    ->label(trans('crud.orders.inputs.total_price'))
                    ->placeholder(trans('crud.orders.inputs.total_price'))
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                Select::make('stauts')
                    ->rules([
                        'required',
                        'in:paid,processing,packed,picked,cancelled',
                    ])
                    ->searchable()
                    ->options([
                        'paid' => 'Paid',
                        'processing' => 'Processing',
                        'packed' => 'Packed',
                        'picked' => 'Picked',
                        'cancelled' => 'Cancelled',
                    ])
                    ->label(trans('crud.orders.inputs.stauts'))
                    ->placeholder(trans('crud.orders.inputs.stauts'))
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
                Tables\Columns\TextColumn::make('number')->limit(50)->label(trans('crud.orders.inputs.number')),
                Tables\Columns\TextColumn::make('total_price')->label(trans('crud.orders.inputs.total_price')),
                Tables\Columns\TextColumn::make('stauts')->label(trans('crud.orders.inputs.stauts'))->enum([
                    'paid' => 'Paid',
                    'processing' => 'Processing',
                    'packed' => 'Packed',
                    'picked' => 'Picked',
                    'cancelled' => 'Cancelled',
                ]),
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
