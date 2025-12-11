@extends('layouts.agent.app')

@section('title', 'Edit Visit')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Visit</h1>
    <p class="text-gray-600">Update visit details and status.</p>
</div>

<div class="bg-white shadow-md rounded-lg p-6">
    <form method="POST" action="{{ route('agent.visits.update', $visit) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Property & Buyer Info</h3>
                <div class="space-y-2 text-sm">
                    <div><strong>Property:</strong> {{ $visit->lead->property->title }}</div>
                    <div><strong>Buyer:</strong> {{ $visit->lead->buyer_name }}</div>
                    <div><strong>Phone:</strong> {{ $visit->lead->buyer_phone }}</div>
                </div>
            </div>

            <div>
                <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-2">Scheduled Date & Time</label>
                <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                       value="{{ $visit->scheduled_at->format('Y-m-d\TH:i') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
            </div>
        </div>

        <div class="mb-6">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="scheduled" {{ $visit->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="completed" {{ $visit->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $visit->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <textarea name="notes" id="notes" rows="4"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Add any notes about this visit...">{{ $visit->notes }}</textarea>
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                Update Visit
            </button>
            <a href="{{ route('agent.visits.show', $visit) }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection