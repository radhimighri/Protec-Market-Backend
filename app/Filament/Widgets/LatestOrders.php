<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;


class LatestOrders extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'letzte Bestellungen';

    protected static ?int $sort = 2;

    public function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    protected function getTableQuery(): Builder
    {
        return OrderResource::getEloquentQuery();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('created_at')
                ->label('Order Date')
                ->label(trans('crud.orders.Order Date'))
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('number')
                ->searchable()
                ->label(trans('crud.orders.inputs.number'))
                ->sortable(),
            Tables\Columns\TextColumn::make('customer.name')
                ->searchable()
                ->label(trans('crud.customers.name'))
                ->sortable(),
            Tables\Columns\BadgeColumn::make('stauts')
                ->colors([
                    'danger' => 'cancelled',
                    'warning' => 'processing',
                    'success' => fn ($state) => in_array($state, ['picked', 'packed']),
                ])
                ->enum([
                    'processing' => trans('crud.orders.options.processing'),
                    'packed' => trans('crud.orders.options.packed'),
                    'picked' => trans('crud.orders.options.picked'),
                    'cancelled' => trans('crud.orders.options.cancelled'),
                ])
                ->label(trans('crud.orders.inputs.stauts')),
            Tables\Columns\TextColumn::make('total_price')
                ->searchable()
                ->label(trans('crud.orders.inputs.total_price'))
                ->sortable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('open')
                ->url(fn (Order $record): string => OrderResource::getUrl('edit', ['record' => $record])),
        ];
    }
}
