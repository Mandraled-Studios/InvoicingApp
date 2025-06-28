<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    public function afterCreate(): void
    {
        // $invoice = Invoice::create($this->form->getState());
        //$invoice = $this->record;
        
        // Save the relationships from the form to the post after it is created.
        //$this->form->model($invoice)->saveRelationships();
    }
}
