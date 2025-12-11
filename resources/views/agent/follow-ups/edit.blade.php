@extends('layouts.agent.app')

@section('title', 'Edit Follow-Up')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Follow-Up</h1>
    <p class="text-gray-600">Update follow-up details and status.</p>
</div>

<div class="bg-white shadow-md rounded-lg p-6">
    <form method="POST" action="{{ route('agent.follow-ups.update', $followUp) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Property & Buyer Info</h3>
                <div class="space-y-2 text-sm">
                    <div><strong>Property:</strong> {{ $followUp->lead->property->title }}</div>
                    <div><strong>Buyer:</strong> {{ $followUp->lead->buyer_name }}</div>
                    <div><strong>Phone:</strong> {{ $followUp->lead->buyer_phone }}</div>
                </div>
            </div>

            <div>
                <label for="follow_up_date" class="block text-sm font-medium text-gray-700 mb-2">Follow-Up Date & Time</label>
                <input type="datetime-local" name="follow_up_date" id="follow_up_date"
                       value="{{ $followUp->follow_up_date->format('Y-m-d\TH:i') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
            </div>
        </div>

        <div class="mb-6">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="pending" {{ $followUp->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $followUp->status == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <textarea name="notes" id="notes" rows="4"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Add any notes about this follow-up...">{{ $followUp->notes }}</textarea>
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                Update Follow-Up
            </button>
            <a href="{{ route('agent.follow-ups.show', $followUp) }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection