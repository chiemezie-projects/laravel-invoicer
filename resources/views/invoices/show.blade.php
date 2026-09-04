@extends('layouts.app')
@section('title',$invoice->invoice_number)
@section('content')
<div class="flex flex-wrap items-start justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold mono">{{ $invoice->invoice_number }}</h1>
            <span class="text-xs px-3 py-1 rounded-full font-bold tracking-widest
                @if($invoice->status==='paid') bg-emerald-100 text-emerald-700
                @elseif($invoice->status==='sent') bg-blue-100 text-blue-700
                @elseif($invoice->status==='overdue') bg-red-100 text-red-700
                @elseif($invoice->status==='void') bg-gray-200 text-gray-600
                @else bg-amber-100 text-amber-700 @endif">{{ strtoupper($invoice->status) }}</span>
            @if($invoice->isOverdue())
                <span class="text-xs bg-red-600 text-white px-2 py-1 rounded-full">OVERDUE</span>
            @endif
        </div>
        <p class="text-sm text-gray-500 mt-1">Issued {{ $invoice->issue_date->format('M j, Y') }} · Due {{ $invoice->due_date->format('M j, Y') }} · {{ $invoice->currency }}</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('invoices.print',$invoice) }}" target="_blank" class="px-4 py-2 rounded-xl border border-gray-200 bg-white text-sm"><i class="ri-printer-line"></i> Print</a>
        <form method="POST" action="{{ route('invoices.duplicate',$invoice) }}">@csrf<button class="px-4 py-2 rounded-xl border border-gray-200 bg-white text-sm"><i class="ri-file-copy-line"></i> Duplicate</button></form>
        <a href="{{ route('invoices.edit',$invoice) }}" class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm">Edit</a>
        <form method="POST" action="{{ route('invoices.destroy',$invoice) }}" onsubmit="return confirm('Delete invoice?')">@csrf @method('DELETE')<button class="px-4 py-2 rounded-xl bg-red-600 text-white text-sm">Delete</button></form>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold">Items</h2>
            <span class="text-xs text-gray-500">{{ $invoice->items->count() }} lines</span>
        </div>
        <table class="w-full text-sm">
            <thead class="text-xs uppercase tracking-widest text-gray-400">
                <tr><th class="text-left px-6 py-2">Description</th><th class="text-right px-3 py-2">Qty</th><th class="text-right px-3 py-2">Price</th><th class="text-right px-6 py-2">Total</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($invoice->items as $item)
                <tr>
                    <td class="px-6 py-3">{{ $item->description }}</td>
                    <td class="px-3 py-3 text-right mono">{{ $item->quantity }}</td>
                    <td class="px-3 py-3 text-right mono">${{ number_format($item->unit_price,2) }}</td>
                    <td class="px-6 py-3 text-right mono font-semibold">${{ number_format($item->line_total,2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="bg-gray-50 px-6 py-4 space-y-1 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span class="mono font-medium">${{ number_format($invoice->subtotal,2) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Tax ({{ $invoice->tax_rate }}%)</span><span class="mono">${{ number_format($invoice->tax_amount,2) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Discount</span><span class="mono">- ${{ number_format($invoice->discount,2) }}</span></div>
            <div class="flex justify-between text-base font-bold pt-2 border-t border-gray-200"><span>Total</span><span class="mono">${{ number_format($invoice->total,2) }} {{ $invoice->currency }}</span></div>
        </div>
        @if($invoice->notes)
            <div class="px-6 py-4 border-t border-gray-100">
                <div class="text-xs uppercase tracking-widest text-gray-400 font-semibold mb-1">Notes</div>
                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $invoice->notes }}</p>
            </div>
        @endif
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="text-xs uppercase tracking-widest text-gray-400 font-semibold">Bill To</div>
            <div class="mt-3">
                <div class="font-semibold">{{ $invoice->client->name }}</div>
                @if($invoice->client->company)<div class="text-sm text-gray-600">{{ $invoice->client->company }}</div>@endif
                <div class="text-sm text-gray-500 mt-2">{{ $invoice->client->email }}<br>{{ $invoice->client->phone }}<br>{{ $invoice->client->address }} {{ $invoice->client->city }}</div>
                @if($invoice->client->vat_number)<div class="text-xs mono mt-2">VAT: {{ $invoice->client->vat_number }}</div>@endif
            </div>
            <a href="{{ route('clients.show',$invoice->client) }}" class="mt-3 inline-block text-xs text-blue-600 hover:underline">View client →</a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="text-xs uppercase tracking-widest text-gray-400 font-semibold">Actions</div>
            <div class="mt-3 grid gap-2">
                <a href="{{ route('invoices.print',$invoice) }}" target="_blank" class="w-full text-center px-4 py-2 rounded-xl border border-gray-200 text-sm bg-white">Print / Save PDF</a>
                <a href="{{ route('invoices.edit',$invoice) }}" class="w-full text-center px-4 py-2 rounded-xl bg-gray-900 text-white text-sm">Edit Invoice</a>
            </div>
        </div>
    </div>
</div>
@endsection
