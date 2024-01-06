<?php

namespace App\Filament\Resources;

use App\Models\Order;
use Filament\{Tables, Forms};
use Filament\Resources\{Form, Table, Resource};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationLabel = 'Bestellungen';

    protected static ?string $title = 'Bestellungen';

    protected static ?string $modelLabel = 'Bestellungen';

    protected static ?string $recordTitleAttribute = 'number';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
                Grid::make(['default' => 0])->schema([
                    TextInput::make('number')
                        ->default('OR-' . random_int(100000, 999999))
                        ->disabled()
                        ->rules(['required', 'max:255', 'string'])
                        ->unique(
                            'orders',
                            'number',
                            fn(?Model $record) => $record
                        )
                        ->label(trans('crud.orders.inputs.number'))
                        ->placeholder(('crud.orders.inputs.number'))
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    TextInput::make('total_price')
                        ->rules(['nullable', 'numeric'])
                        ->numeric()
                        ->disabled()
                        ->label(trans('crud.orders.inputs.total_price'))
                        ->placeholder(('crud.orders.inputs.total_price'))
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                        Select::make('payment_method')
                        ->rules([
                            'required',
                            'in:stripe,paypal,pay on site',
                        ])
                        ->searchable()
                        ->disabled()
                        ->options([
                            'stripe' => 'Stripe',
                            'paypal' => 'Paypal',
                            'pay on site'=> 'Pay on site',
                        ])
                        ->label(trans('crud.orders.inputs.payment_method'))
                        ->placeholder(trans('crud.orders.inputs.payment_method'))
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                    Select::make('stauts')
                        ->rules([
                            'required',
                            'in:processing,packed,picked,cancelled',
                        ])
                        ->searchable()
                        ->options([
                            'processing' => trans('crud.orders.options.processing'),
                            'packed' => trans('crud.orders.options.packed'),
                            'picked' => trans('crud.orders.options.picked'),
                            'cancelled' => trans('crud.orders.options.cancelled'),
                        ])
                        ->label(trans('crud.orders.inputs.stauts'))
                        ->placeholder(trans('crud.orders.inputs.stauts'))
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),
                        Select::make('payment_status')
                        ->rules([
                            'required',
                            'in:paid,not paid',
                        ])
                        ->searchable()
                        ->options([
                            'paid' => trans('crud.orders.options.paid'),
                            'not paid' => trans('crud.orders.options.not paid'),

                        ])
                        ->label(trans('crud.orders.inputs.payment_status'))
                        ->placeholder(trans('crud.orders.inputs.payment_status'))
                        ->columnSpan([
                            'default' => 12,
                            'md' => 12,
                            'lg' => 12,
                        ]),

                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                ->label(trans('crud.orders.Order Date'))
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('number')
                ->label(trans('crud.orders.inputs.number'))
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('customer.name')
                ->label(trans('crud.customers.name'))
                ->searchable()
                ->sortable(),
            Tables\Columns\BadgeColumn::make('stauts')
                ->colors([
                    'danger' => 'cancelled',
                    'warning' => 'processing',
                    'success' => fn ($state) => in_array($state, ['packed', 'picked']),
                ])
                ->enum([
                    'processing' => trans('crud.orders.options.processing'),
                    'packed' => trans('crud.orders.options.packed'),
                    'picked' => trans('crud.orders.options.picked'),
                    'cancelled' => trans('crud.orders.options.cancelled'),
                ])
                ->label(trans('crud.orders.inputs.stauts')),
                Tables\Columns\BadgeColumn::make('payment_method')
                ->colors([
                    'success' => 'paypal',
                    'warning' => 'stripe',
                    'danger' => 'pay on site',
                ])
                ->label(trans('crud.orders.inputs.payment_method'))
                ->enum([
                    'pay on site'=>trans('crud.orders.options.pay on site')
                ]),
                Tables\Columns\BadgeColumn::make('payment_status')
                ->colors([
                    'success' => 'paid',
                    'danger' => 'not paid',
                    
                ])
                ->label(trans('crud.orders.inputs.payment_status'))
                ->enum([
                    'paid'=>trans('crud.orders.options.paid'),
                    'not paid'=>trans('crud.orders.options.not paid')
                ]),
            Tables\Columns\TextColumn::make('total_price')
                ->searchable()
                ->label(trans('crud.orders.inputs.total_price'))
                ->sortable(),
                Tables\Columns\TextColumn::make('pick_up_date')
                ->label(trans('crud.orders.inputs.pick_up_date'))
                ->datetime()
                ->sortable(),
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
        public static function getWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }

    public static function getRelations(): array
    {
        return [OrderResource\RelationManagers\ProductsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['number', 'customer.name'];
    }
    protected static function getNavigationBadge(): ?string
    {
        return static::$model::where('stauts', 'processing')->count();
    }
}

