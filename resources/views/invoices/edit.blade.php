@extends('layouts.app')
@section('title','Edit '.$invoice->invoice_number)
@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('invoices.show',$invoice) }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-flex items-center gap-1"><i class="ri-arrow-left-line"></i> Back to invoice</a>
    <h1 class="text-xl font-bold mb-6">Edit {{ $invoice->invoice_number }}</h1>

    <form method="POST" action="{{ route('invoices.update',$invoice) }}" class="bg-white rounded-2xl border border-gray-200 p-6">
        @csrf @method('PUT')
        @include('invoices._form')
        <div class="mt-8 flex gap-2">
            <button class="bg-gray-900 text-white px-6 py-2.5 rounded-xl text-sm font-semibold">Save Changes</button>
            <a href="{{ route('invoices.show',$invoice) }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
