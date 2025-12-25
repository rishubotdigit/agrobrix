@extends('layouts.admin.app')

@section('title', 'Contact Message Details')

@section('content')
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Contact Message Details</h1>
            <a href="{{ route('admin.contact-messages.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <p class="mt-1 text-sm text-gray-900">{{ $contactMessage->name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 text-sm text-gray-900">{{ $contactMessage->email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <p class="mt-1 text-sm text-gray-900">{{ $contactMessage->phone ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Subject</label>
                <p class="mt-1 text-sm text-gray-900">{{ $contactMessage->subject }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Message</label>
                <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $contactMessage->message }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <p class="mt-1 text-sm text-gray-900">{{ $contactMessage->created_at->format('M j, Y H:i') }}</p>
            </div>
        </div>
    </div>
@endsection