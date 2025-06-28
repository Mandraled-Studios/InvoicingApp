<?php

namespace App\Filament\Resources\InvoiceResource\Widgets;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Resources\InvoiceResource\Pages\ListInvoices;

class InvoiceBasicStats extends BaseWidget
{
    use InteractsWithPageTable;
    protected static ?string $pollingInterval = '5s';

    protected function getTablePage() {
        return ListInvoices::class;
    }
    protected function getStats(): array
    {
        
        //Total Invoices
        $invoice_count = $this->getPageTableQuery()->count();
        
        //Total Invoice Amount
        $invoice_total = $this->getPageTableQuery()->sum('invoice_total');
        
        //Biggest Invoice
        $invoice_max = $this->getPageTableQuery()->max('invoice_total');
        
        //Best Recurring Client
        $grouping = Invoice::groupBy('client_id')->selectRaw('sum(invoice_total) as total, client_id')->get()->sortByDesc('total')->first();
        $best_client = Client::find($grouping->client_id);
        $client_lifetime_bill_value = $grouping->total;

        $big_invoice_client = $this->getPageTableQuery()->get()->sortByDesc('invoice_total')->first();
        
        return [
            Stat::make('Invoice Total', '₹'.number_format($invoice_total, 2, '.', ','))->description('Invoice Count:'.$invoice_count),
            Stat::make('Biggest Single Invoice', '₹'.number_format($big_invoice_client->invoice_total, 2, '.', ','))->description('Client: '.$big_invoice_client->client->company_name),
            Stat::make('Best Lifetime Client', '₹'.number_format($client_lifetime_bill_value, 2, '.', ','))->description($best_client->company_name),
                //Stat::make('Invoice Count', $this->getPageTableQuery()->count()),
                //Stat::make('Best Invoice', '₹'.$this->getPageTableQuery()->orderBy('client_id')->get()),
                //Stat::make('Best Client', $client_max)->description($client->company_name),
            ];
        }
}
