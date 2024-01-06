<?php

namespace App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\OrderStats;
use Filament\Pages\Actions;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;
     protected function getActions(): array
    {
       return [ ]; 
    }

    protected function getHeaderWidgets(): array
    {
        return OrderResource::getWidgets();
    }

}
