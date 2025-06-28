<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\InvoiceResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use App\Filament\Resources\InvoiceResource\Widgets\TopClientStat;
use App\Filament\Resources\InvoiceResource\Widgets\InvoiceBasicStats;

class ListInvoices extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array {
        return [
            InvoiceBasicStats::class,
            //TopClientStat::class,
        ];
    }
}
