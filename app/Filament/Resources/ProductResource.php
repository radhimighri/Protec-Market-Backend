<?php

namespace App\Filament\Resources;

use App\Models\Product;
use Filament\{Tables, Forms};
use Filament\Resources\{Form, Table, Resource};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\Widgets\ProductStats;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationLabel = 'Produkte';

    protected static ?string $title = 'Produkte';

    protected static ?string $modelLabel = 'Produkte';

    protected static ?string $slug = 'products';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-lightning-bolt';

    protected static ?string $navigationGroup = 'Shop';


    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()->schema([
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
                        ->rules(['required', 'numeric','regex:/^\d{1,6}(\.\d{0,2})?$/'])
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
                Forms\Components\Section::make(trans('crud.products.inputs.stock'))
                ->schema([
             /*        Forms\Components\Select::make('shop_brand_id')
                        ->relationship('ca', 'name')
                        ->searchable()
                        ->hiddenOn(ProductsRelationManager::class), */

                    Forms\Components\MultiSelect::make('categories')
                        ->relationship('categories', 'name')
                        ->label(trans('crud.categories.name'))
                        ->required()
                        ->searchable(),
                ]),

            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('picture')->searchable()
                ->label(trans('crud.products.inputs.picture'))
                ->sortable(),
                //->toggleable()->rounded(),
                Tables\Columns\TextColumn::make('name')->searchable()
                ->label(trans('crud.products.inputs.name'))
                ->sortable()
                //->toggleable()
                ->limit(50),
                Tables\Columns\TextColumn::make('description')->limit(50)->searchable()
                ->label(trans('crud.products.inputs.description'))
                ->sortable(),
                //->toggleable(),
                Tables\Columns\TextColumn::make('price')->searchable()
                ->label(trans('crud.products.inputs.price'))
                ->sortable(),
                //->toggleable(),
                Tables\Columns\TextColumn::make('weight')->searchable()
                ->sortable()
                ->label(trans('crud.products.inputs.weight')),
                //->toggleable(),
                Tables\Columns\TextColumn::make('categories.name')
                ->searchable()
                ->label(trans('crud.categories.name'))
                ->sortable(),
                //->toggleable(),
                Tables\Columns\TextColumn::make('stock')->searchable()
                ->label(trans('crud.products.inputs.stock'))
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

    public static function getRelations(): array
    {
        return [
            ProductResource\RelationManagers\ReviewsRelationManager::class,
            //ProductResource\RelationManagers\OrdersRelationManager::class,
            ProductResource\RelationManagers\CategoriesRelationManager::class,
        ];
    }
    public static function getWidgets(): array
    {
        return [
            ProductStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
    

}
