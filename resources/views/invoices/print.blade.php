<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>{{ $invoice->invoice_number }} — Print</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=JetBrains+Mono:wght@400;600&display=swap');
  body{ font-family:Inter, sans-serif; }
  .mono{ font-family: 'JetBrains Mono', monospace; }
  @media print { .no-print{display:none} }
</style>
</head>
<body class="bg-white text-gray-900 p-8 max-w-3xl mx-auto">
<div class="flex justify-between items-start border-b-2 border-gray-900 pb-6 mb-6">
    <div>
        <div class="text-2xl font-bold">INVOICE</div>
        <div class="mono text-lg font-semibold mt-1">{{ $invoice->invoice_number }}</div>
        <div class="text-xs uppercase tracking-widest mt-1
            @if($invoice->status==='paid') text-emerald-600
            @elseif($invoice->status==='overdue') text-red-600
            @else text-gray-500 @endif">{{ $invoice->status }}</div>
    </div>
    <div class="text-right text-sm">
        <div class="font-bold">Mini Invoicer</div>
        <div class="text-gray-500">hello@mini-invoicer.test<br>123 Business St, City</div>
        <div class="mt-2 text-xs">Issue: {{ $invoice->issue_date->format('Y-m-d') }}<br>Due: {{ $invoice->due_date->format('Y-m-d') }}</div>
    </div>
</div>

<div class="grid grid-cols-2 gap-8 mb-8 text-sm">
    <div>
        <div class="text-xs uppercase tracking-widest text-gray-400 font-semibold">Bill To</div>
        <div class="mt-2 font-semibold">{{ $invoice->client->name }}</div>
        <div>{{ $invoice->client->company }}</div>
        <div class="text-gray-600">{{ $invoice->client->email }}<br>{{ $invoice->client->phone }}<br>{{ $invoice->client->address }} {{ $invoice->client->city }}<br>@if($invoice->client->vat_number) VAT: {{ $invoice->client->vat_number }} @endif</div>
    </div>
    <div class="text-right">
        <div class="text-xs uppercase tracking-widest text-gray-400 font-semibold">Amount Due</div>
        <div class="text-3xl font-bold mono mt-1">${{ number_format($invoice->total,2) }}</div>
        <div class="text-xs text-gray-500">{{ $invoice->currency }}</div>
    </div>
</div>

<table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
    <thead class="bg-gray-900 text-white text-xs uppercase tracking-widest">
        <tr><th class="text-left px-4 py-2">Description</th><th class="text-right px-4 py-2">Qty</th><th class="text-right px-4 py-2">Unit</th><th class="text-right px-4 py-2">Amount</th></tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
        @foreach($invoice->items as $item)
        <tr><td class="px-4 py-2">{{ $item->description }}</td><td class="px-4 py-2 text-right mono">{{ $item->quantity }}</td><td class="px-4 py-2 text-right mono">${{ number_format($item->unit_price,2) }}</td><td class="px-4 py-2 text-right mono font-semibold">${{ number_format($item->line_total,2) }}</td></tr>
        @endforeach
    </tbody>
</table>

<div class="mt-6 ml-auto max-w-xs text-sm space-y-1">
    <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span class="mono">${{ number_format($invoice->subtotal,2) }}</span></div>
    <div class="flex justify-between"><span class="text-gray-500">Tax {{ $invoice->tax_rate }}%</span><span class="mono">${{ number_format($invoice->tax_amount,2) }}</span></div>
    <div class="flex justify-between"><span class="text-gray-500">Discount</span><span class="mono">- ${{ number_format($invoice->discount,2) }}</span></div>
    <div class="flex justify-between font-bold border-t border-gray-900 pt-2 text-base"><span>Total</span><span class="mono">${{ number_format($invoice->total,2) }}</span></div>
</div>

@if($invoice->notes)
<div class="mt-8 text-sm border-t border-gray-200 pt-4">
    <div class="text-xs uppercase tracking-widest text-gray-400 font-semibold">Notes</div>
    <p class="mt-1 text-gray-700 whitespace-pre-wrap">{{ $invoice->notes }}</p>
</div>
@endif

<div class="mt-10 text-center text-xs text-gray-400">Thank you for your business!</div>

<div class="no-print mt-8 flex justify-center gap-2">
    <button onclick="window.print()" class="px-6 py-2 bg-gray-900 text-white rounded-xl text-sm">Print</button>
    <a href="{{ route('invoices.show',$invoice) }}" class="px-6 py-2 border border-gray-300 rounded-xl text-sm">Back</a>
</div>
</body>
</html>
