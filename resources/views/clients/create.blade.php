@extends('layouts.app')
@section('title','New Client')
@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('clients.index') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-flex items-center gap-1"><i class="ri-arrow-left-line"></i> Back to clients</a>
    <h1 class="text-xl font-bold mb-6">Add Client</h1>
    <form method="POST" action="{{ route('clients.store') }}" class="bg-white rounded-2xl border border-gray-200 p-6">
        @csrf
        @include('clients._form')
        <div class="mt-6 flex gap-2">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium">Save Client</button>
            <a href="{{ route('clients.index') }}" class="px-5 py-2 rounded-lg border border-gray-200 text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
