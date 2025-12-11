@extends('layouts.agent.app')

@section('title', 'Schedule Follow-Up')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Schedule Follow-Up</h1>
    <p class="text-gray-600">Schedule a follow-up call/message for {{ $lead->buyer_name }} regarding {{ $lead->property->title }}.</p>
</div>

<div class="bg-white shadow-md rounded-lg p-6">
    <form method="POST" action="{{ route('agent.follow-ups.store', $lead) }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Property Information</h3>
                <div class="space-y-2 text-sm">
                    <div><strong>Title:</strong> {{ $lead->property->title }}</div>
                    <div><strong>Location:</strong> {{ $lead->property->area }}, {{ $lead->property->city }}</div>
                    <div><strong>Price:</strong> ₹{{ number_format($lead->property->price) }}</div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Buyer Information</h3>
                <div class="space-y-2 text-sm">
                    <div><strong>Name:</strong> {{ $lead->buyer_name }}</div>
                    <div><strong>Email:</strong> {{ $lead->buyer_email }}</div>
                    <div><strong>Phone:</strong> {{ $lead->buyer_phone }}</div>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <label for="follow_up_date" class="block text-sm font-medium text-gray-700 mb-2">Follow-Up Date & Time *</label>
            <input type="datetime-local" name="follow_up_date" id="follow_up_date"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   required>
            <p class="text-sm text-gray-500 mt-1">Select a date and time for the follow-up</p>
        </div>

        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <textarea name="notes" id="notes" rows="4"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Add any notes or script for this follow-up call/message..."></textarea>
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                Schedule Follow-Up
            </button>
            <a href="{{ route('agent.leads.show', $lead) }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection