@extends('layouts.app')
@section('title',$client->name)
@section('content')
<div class="flex items-start justify-between gap-4 mb-6">
    <div class="flex gap-4">
        <span class="w-14 h-14 rounded-2xl bg-indigo-600 text-white grid place-items-center text-xl font-bold">{{ strtoupper(substr($client->name,0,1)) }}</span>
        <div>
            <h1 class="text-xl font-bold">{{ $client->name }}</h1>
            <p class="text-sm text-gray-500">{{ $client->company ?? '—' }} @if($client->email) · {{ $client->email }} @endif</p>
            <p class="text-xs text-gray-400 mt-1">{{ $client->address }} {{ $client->city }}</p>
        </div>
    </div>
    <div class="flex gap-2 shrink-0">
        <a href="{{ route('clients.edit',$client) }}" class="px-4 py-2 rounded-lg border border-gray-200 bg-white text-sm">Edit</a>
        <a href="{{ route('invoices.create') }}?client={{ $client->id }}" class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm">New Invoice</a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-semibold text-sm">Invoices · {{ $client->invoices->count() }}</h2>
        <span class="text-xs text-gray-500 mono">VAT: {{ $client->vat_number ?? '—' }}</span>
    </div>
    @if($client->invoices->isEmpty())
        <div class="p-8 text-center text-sm text-gray-500">No invoices for this client.</div>
    @else
    <div class="divide-y divide-gray-100">
        @foreach($client->invoices->sortByDesc('created_at') as $inv)
        <a href="{{ route('invoices.show',$inv) }}" class="flex items-center justify-between px-5 py-3 hover:bg-gray-50">
            <span class="mono text-sm font-medium">{{ $inv->invoice_number }}</span>
            <span class="text-xs px-2 py-1 rounded-full
                @if($inv->status==='paid') bg-emerald-100 text-emerald-700
                @elseif($inv->status==='sent') bg-blue-100 text-blue-700
                @elseif($inv->status==='overdue') bg-red-100 text-red-700
                @else bg-gray-100 text-gray-600 @endif">{{ strtoupper($inv->status) }}</span>
            <span class="mono text-sm font-semibold">${{ number_format($inv->total,2) }}</span>
            <span class="text-xs text-gray-500">{{ $inv->due_date->format('Y-m-d') }}</span>
        </a>
        @endforeach
    </div>
    @endif
</div>
@endsection
