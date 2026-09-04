<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Invoice::with(['client', 'items']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($q = $request->get('q')) {
            $query->where(function ($w) use ($q) {
                $w->where('invoice_number', 'like', "%{$q}%")
                  ->orWhereHas('client', fn ($c) => $c->where('name', 'like', "%{$q}%"));
            });
        }

        $invoices = $query->latest()->paginate(10)->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        $clients = Client::orderBy('name')->get();
        $invoice = new Invoice([
            'invoice_number' => Invoice::nextInvoiceNumber(),
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(14)->toDateString(),
            'status' => 'draft',
            'tax_rate' => 0,
            'discount' => 0,
            'currency' => 'USD',
        ]);

        return view('invoices.create', compact('clients', 'invoice'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateInvoice($request);
        $invoice = Invoice::create($this->extractInvoiceData($data));
        $this->syncItems($invoice, $data['items'] ?? []);

        return redirect()->route('invoices.show', $invoice)->with('status', 'Invoice created.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['client', 'items']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        $invoice->load('items');
        $clients = Client::orderBy('name')->get();
        return view('invoices.edit', compact('invoice', 'clients'));
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $data = $this->validateInvoice($request, $invoice);
        $invoice->update($this->extractInvoiceData($data));
        $this->syncItems($invoice, $data['items'] ?? []);

        return redirect()->route('invoices.show', $invoice)->with('status', 'Invoice updated.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('status', 'Invoice deleted.');
    }

    public function duplicate(Invoice $invoice): RedirectResponse
    {
        $new = $invoice->replicate();
        $new->invoice_number = Invoice::nextInvoiceNumber();
        $new->status = 'draft';
        $new->issue_date = now();
        $new->due_date = now()->addDays(14);
        $new->save();

        foreach ($invoice->items as $item) {
            $new->items()->create($item->only(['description', 'quantity', 'unit_price']));
        }

        return redirect()->route('invoices.edit', $new)->with('status', 'Invoice duplicated as draft.');
    }

    private function validateInvoice(Request $request, ?Invoice $invoice = null): array
    {
        $ignore = $invoice ? ',' . $invoice->id : '';
        return $request->validate([
            'invoice_number' => ['required', 'string', 'max:50', 'unique:invoices,invoice_number' . $ignore],
            'client_id' => ['required', 'exists:clients,id'],
            'status' => ['required', 'in:draft,sent,paid,overdue,void'],
            'issue_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:issue_date'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);
    }

    private function extractInvoiceData(array $data): array
    {
        return collect($data)->only([
            'invoice_number', 'client_id', 'status', 'issue_date', 'due_date',
            'tax_rate', 'discount', 'currency', 'notes',
        ])->toArray();
    }

    private function syncItems(Invoice $invoice, array $items): void
    {
        $invoice->items()->delete();
        foreach ($items as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
            ]);
        }
    }
}
