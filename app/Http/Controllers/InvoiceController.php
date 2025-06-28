<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
//use Barryvdh\DomPDF\PDF;
//use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Options;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    
    public function showPDF ($id) {
        $invoice = Invoice::findOrFail($id);
        $data = [
            'invoice' => $invoice,
            'client' => $invoice->client,
            'line_items' => $invoice->invoiceProducts,
            // 'invoice_number' => $invoice->invoice_number,
            // 'invoice_date' => $invoice->invoice_date,
            // 'invoice_duedate' => $invoice->invoice_duedate,
            // 'invoice_total' => $invoice->invoice_total,
        ];
        
        $pdf = PDF::loadView('invoice', $data)->setPaper('a4', 'portrait')->setWarnings(false);
        $pdf->setOptions([
            'isRemoteEnabled' => true
        ]);
        
        return $pdf->stream();
    }
    public function showInvoiceInWeb ($id) {
        $invoice = Invoice::findOrFail($id);
        
        return view('invoice-web')->with([
            'invoice' => $invoice,
            'client' => $invoice->client,
            'line_items' => $invoice->invoiceProducts,
        ]);
    }

    public function showInvoice() {
        return view('invoice2');
    }
}
