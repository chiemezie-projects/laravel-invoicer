@extends('layouts.app')
@section('title','Invoices — Mini Invoicer')
@section('content')
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 mb-6">
    <h1 class="text-xl font-bold">Invoices <span class="text-gray-400 font-normal text-sm">({{ $invoices->total() }})</span></h1>
    <div class="flex flex-wrap gap-2">
        <form method="GET" class="flex gap-2">
            <input name="q" value="{{ request('q') }}" placeholder="Search # or client" class="rounded-lg border border-gray-300 px-3 py-2 text-sm w-44">
            <select name="status" onchange="this.form.submit()" class="rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white">
                <option value="">All statuses</option>
                @foreach(['draft','sent','paid','overdue','void'] as $s)
                    <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button class="px-3 py-2 rounded-lg border border-gray-200 bg-white text-sm">Filter</button>
            @if(request('q')||request('status'))
                <a href="{{ route('invoices.index') }}" class="px-3 py-2 rounded-lg bg-gray-100 text-sm">Clear</a>
            @endif
        </form>
        <a href="{{ route('invoices.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">New Invoice</a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    @if($invoices->isEmpty())
        <div class="p-10 text-center">
            <div class="w-12 h-12 rounded-2xl bg-gray-100 grid place-items-center mx-auto mb-3"><i class="ri-file-list-3-line text-xl text-gray-500"></i></div>
            <p class="text-sm text-gray-500">No invoices match.</p>
        </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase tracking-widest text-gray-500">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold">Invoice</th>
                    <th class="text-left px-3 py-3 font-semibold">Client</th>
                    <th class="text-left px-3 py-3 font-semibold">Dates</th>
                    <th class="text-center px-3 py-3 font-semibold">Status</th>
                    <th class="text-right px-5 py-3 font-semibold">Total</th>
                    <th class="px-3 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($invoices as $inv)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <a href="{{ route('invoices.show',$inv) }}" class="mono font-semibold text-gray-900 hover:text-blue-600">{{ $inv->invoice_number }}</a>
                        <div class="text-xs text-gray-500">{{ $inv->currency }}</div>
                    </td>
                    <td class="px-3 py-3">
                        <div class="font-medium">{{ $inv->client->name }}</div>
                        <div class="text-xs text-gray-500">{{ $inv->client->company ?? '' }}</div>
                    </td>
                    <td class="px-3 py-3 text-xs text-gray-600">
                        <div>Issued {{ $inv->issue_date->format('Y-m-d') }}</div>
                        <div class="{{ $inv->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }}">Due {{ $inv->due_date->format('Y-m-d') }}</div>
                    </td>
                    <td class="px-3 py-3 text-center">
                        <span class="text-[11px] px-2.5 py-1 rounded-full font-semibold tracking-wide
                            @if($inv->status==='paid') bg-emerald-100 text-emerald-700
                            @elseif($inv->status==='sent') bg-blue-100 text-blue-700
                            @elseif($inv->status==='overdue') bg-red-100 text-red-700
                            @elseif($inv->status==='void') bg-gray-100 text-gray-500 line-through
                            @else bg-amber-100 text-amber-700 @endif">{{ strtoupper($inv->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-right mono font-bold">${{ number_format($inv->total,2) }}</td>
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-1 justify-end">
                            <a href="{{ route('invoices.show',$inv) }}" class="w-7 h-7 grid place-items-center rounded-lg border border-gray-200 hover:bg-white"><i class="ri-eye-line text-gray-600 text-sm"></i></a>
                            <a href="{{ route('invoices.edit',$inv) }}" class="w-7 h-7 grid place-items-center rounded-lg border border-gray-200 hover:bg-white"><i class="ri-pencil-line text-gray-600 text-sm"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $invoices->links() }}</div>
    @endif
</div>
@endsection
