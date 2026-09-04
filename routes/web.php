<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('clients', ClientController::class);
Route::resource('invoices', InvoiceController::class);
Route::post('invoices/{invoice}/duplicate', [InvoiceController::class, 'duplicate'])->name('invoices.duplicate');

// Printable view
Route::get('invoices/{invoice}/print', function (\App\Models\Invoice $invoice) {
    $invoice->load(['client', 'items']);
    return view('invoices.print', compact('invoice'));
})->name('invoices.print');
