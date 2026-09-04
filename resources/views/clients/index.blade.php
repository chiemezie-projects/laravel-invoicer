@extends('layouts.app')
@section('title','Clients — Mini Invoicer')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
    <h1 class="text-xl font-bold">Clients <span class="text-gray-400 font-normal text-sm">({{ $clients->total() }})</span></h1>
    <div class="flex gap-2">
        <form method="GET" class="flex-1 sm:w-64">
            <input name="q" value="{{ request('q') }}" placeholder="Search name, company, email..." class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </form>
        <a href="{{ route('clients.create') }}" class="shrink-0 bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium">Add Client</a>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    @if($clients->isEmpty())
        <div class="p-10 text-center">
            <div class="w-12 h-12 rounded-2xl bg-gray-100 grid place-items-center mx-auto mb-3"><i class="ri-group-line text-xl text-gray-500"></i></div>
            <p class="text-sm text-gray-500">No clients found.</p>
            <a href="{{ route('clients.create') }}" class="text-sm text-blue-600 hover:underline">Create your first client</a>
        </div>
    @else
    <div class="divide-y divide-gray-100">
        @foreach($clients as $client)
        <div class="flex items-center justify-between px-5 py-4 hover:bg-gray-50">
            <a href="{{ route('clients.show',$client) }}" class="flex items-center gap-3 flex-1 min-w-0">
                <span class="w-10 h-10 rounded-xl bg-indigo-600 text-white grid place-items-center font-semibold">{{ strtoupper(substr($client->name,0,1)) }}</span>
                <div class="min-w-0">
                    <div class="text-sm font-semibold truncate">{{ $client->name }} @if($client->company)<span class="text-gray-400 font-normal">· {{ $client->company }}</span>@endif</div>
                    <div class="text-xs text-gray-500 truncate">{{ $client->email ?? 'no email' }} · {{ $client->invoices_count }} invoices</div>
                </div>
            </a>
            <div class="hidden sm:flex items-center gap-1">
                <a href="{{ route('clients.edit',$client) }}" class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-medium hover:bg-white">Edit</a>
                <form method="POST" action="{{ route('clients.destroy',$client) }}" onsubmit="return confirm('Delete client? Invoices will be deleted too.')">
                    @csrf @method('DELETE')
                    <button class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 text-xs font-medium hover:bg-red-100">Delete</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $clients->links() }}</div>
    @endif
</div>
@endsection
