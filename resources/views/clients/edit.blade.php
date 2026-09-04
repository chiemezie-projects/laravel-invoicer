@extends('layouts.app')
@section('title','Edit Client')
@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('clients.show',$client) }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-flex items-center gap-1"><i class="ri-arrow-left-line"></i> Back</a>
    <h1 class="text-xl font-bold mb-6">Edit Client</h1>
    <form method="POST" action="{{ route('clients.update',$client) }}" class="bg-white rounded-2xl border border-gray-200 p-6">
        @csrf @method('PUT')
        @include('clients._form')
        <div class="mt-6 flex gap-2">
            <button class="bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium">Update Client</button>
            <a href="{{ route('clients.show',$client) }}" class="px-5 py-2 rounded-lg border border-gray-200 text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
