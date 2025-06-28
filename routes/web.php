<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::get('/', function () {
    return redirect('/console');
});

Route::get('/console/invoices/{id}/view-pdf', [InvoiceController::class,'showPDF']);
Route::get('/console/invoices/{id}/webview', [InvoiceController::class,'showInvoiceInWeb']);
