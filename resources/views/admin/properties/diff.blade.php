@extends('layouts.admin.app')

@section('title', 'Version Diff')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Version {{ $version->version }} Diff</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Changes in Version {{ $version->version }}</h2>

        @if($previous)
            <div class="mb-4">
                <h3 class="text-lg font-medium">Previous Version ({{ $previous->version }})</h3>
                <pre class="bg-gray-100 p-4 rounded text-sm overflow-x-auto">{{ json_encode($previous->data, JSON_PRETTY_PRINT) }}</pre>
            </div>
            <div class="mb-4">
                <h3 class="text-lg font-medium">Current Version ({{ $version->version }})</h3>
                <pre class="bg-gray-100 p-4 rounded text-sm overflow-x-auto">{{ json_encode($version->data, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @else
            <p class="text-gray-600">This is the first version. No previous version to compare.</p>
            <pre class="bg-gray-100 p-4 rounded text-sm overflow-x-auto">{{ json_encode($version->data, JSON_PRETTY_PRINT) }}</pre>
        @endif

        <div class="mt-6">
            <a href="{{ route('admin.properties.versions', $version->property) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Back to Versions</a>
        </div>
    </div>
@endsection