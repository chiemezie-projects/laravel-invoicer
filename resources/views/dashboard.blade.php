@extends('layouts.app')
@section('title','Dashboard — Mini Invoicer')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Overview of invoices & clients</p>
    </div>
    <div class="hidden sm:flex gap-2">
        <a href="{{ route('invoices.create') }}" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-black">Create Invoice</a>
    </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-gray-200 p-5">
        <div class="text-xs uppercase tracking-widest text-gray-500 font-semibold">Paid Revenue</div>
        <div class="mt-2 text-2xl font-bold mono">${{ number_format($stats['total_revenue'],2) }}</div>
        <div class="mt-1 text-xs text-emerald-600 flex items-center gap-1"><i class="ri-arrow-up-line"></i> collected</div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-5">
        <div class="text-xs uppercase tracking-widest text-gray-500 font-semibold">Outstanding</div>
        <div class="mt-2 text-2xl font-bold mono">${{ number_format($stats['outstanding'],2) }}</div>
        <div class="mt-1 text-xs text-amber-600">{{ $stats['overdue_count'] }} overdue</div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-5">
        <div class="text-xs uppercase tracking-widest text-gray-500 font-semibold">Drafts</div>
        <div class="mt-2 text-2xl font-bold mono">{{ $stats['draft_count'] }}</div>
        <div class="mt-1 text-xs text-gray-500">awaiting send</div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-5">
        <div class="text-xs uppercase tracking-widest text-gray-500 font-semibold">Clients</div>
        <div class="mt-2 text-2xl font-bold mono">{{ $stats['client_count'] }}</div>
        <a href="{{ route('clients.index') }}" class="text-xs text-blue-600 hover:underline">View all →</a>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold">Recent Invoices</h2>
            <a href="{{ route('invoices.index') }}" class="text-sm text-blue-600 hover:underline">View all</a>
        </div>
        @if($recentInvoices->isEmpty())
            <div class="p-8 text-center text-sm text-gray-500">No invoices yet. <a href="{{ route('invoices.create') }}" class="text-blue-600 hover:underline">Create one</a></div>
        @else
        <div class="divide-y divide-gray-100">
            @foreach($recentInvoices as $inv)
            <a href="{{ route('invoices.show',$inv) }}" class="flex items-center justify-between px-5 py-4 hover:bg-gray-50">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-gray-900 text-white grid place-items-center mono text-xs">{{ substr($inv->invoice_number,-4) }}</span>
                    <div>
                        <div class="text-sm font-medium mono">{{ $inv->invoice_number }} <span class="text-gray-400">·</span> {{ $inv->client->name }}</div>
                        <div class="text-xs text-gray-500">{{ $inv->issue_date->format('M j, Y') }} → {{ $inv->due_date->format('M j') }} · {{ ucfirst($inv->status) }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-semibold mono">${{ number_format($inv->total,2) }}</div>
                    <span class="inline-block text-[11px] px-2 py-0.5 rounded-full
                        @if($inv->status==='paid') bg-emerald-100 text-emerald-700
                        @elseif($inv->status==='sent') bg-blue-100 text-blue-700
                        @elseif($inv->status==='overdue') bg-red-100 text-red-700
                        @elseif($inv->status==='void') bg-gray-100 text-gray-600
                        @else bg-amber-100 text-amber-700 @endif
                    ">{{ strtoupper($inv->status) }}</span>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold">Clients</h2>
            <a href="{{ route('clients.index') }}" class="text-sm text-blue-600 hover:underline">Manage</a>
        </div>
        @if($recentClients->isEmpty())
            <div class="p-8 text-center text-sm text-gray-500">No clients. <a href="{{ route('clients.create') }}" class="text-blue-600 hover:underline">Add client</a></div>
        @else
        <div class="divide-y divide-gray-100">
            @foreach($recentClients as $c)
            <a href="{{ route('clients.show',$c) }}" class="flex items-center gap-3 px-5 py-3.5 hover:bg-gray-50">
                <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-700 grid place-items-center text-sm font-semibold">{{ strtoupper(substr($c->name,0,1)) }}</span>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium truncate">{{ $c->name }}</div>
                    <div class="text-xs text-gray-500 truncate">{{ $c->company ?? $c->email ?? '—' }}</div>
                </div>
                <i class="ri-arrow-right-s-line text-gray-400"></i>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
