@extends('layouts.app')
@section('title','New Invoice')
@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('invoices.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-flex items-center gap-1"><i class="ri-arrow-left-line"></i> Back to invoices</a>
    <h1 class="text-xl font-bold mb-2">Create Invoice</h1>
    <p class="text-sm text-gray-500 mb-6">Add line items, set tax & discount. Totals computed automatically.</p>

    <form method="POST" action="{{ route('invoices.store') }}" class="bg-white rounded-2xl border border-gray-200 p-6">
        @csrf
        @include('invoices._form')
        <div class="mt-8 flex gap-2">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold">Create Invoice</button>
            <a href="{{ route('invoices.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
