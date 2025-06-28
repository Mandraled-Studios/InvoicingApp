<?php

namespace App\Filament\Resources\InvoiceResource\Widgets;

use App\Models\Client;
use App\Models\Invoice;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TopClientStat extends BaseWidget
{
    protected function getStats(): array
    {
        $top_client = Client::find(1);
        $client_name = $top_client->company_name;
        $invoice_count = Invoice::where('client_id', $top_client->id)->count();
        $invoice_total = Invoice::where('client_id', $top_client->id)->sum('invoice_total');

        return [
            Stat::make('Top Client', $client_name),
            Stat::make('Invoice Count', $invoice_count),
            Stat::make('Invoice Total', $invoice_total),
        ];
    }
}
