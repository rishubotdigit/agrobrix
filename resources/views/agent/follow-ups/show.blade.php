@extends('layouts.agent.app')

@section('title', 'Follow-Up Details')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Follow-Up Details</h1>
    <p class="text-gray-600">View follow-up information and manage status.</p>
</div>

<div class="bg-white shadow-md rounded-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Property Information</h3>
            <div class="space-y-2">
                <div><strong>Title:</strong> {{ $followUp->lead->property->title }}</div>
                <div><strong>Location:</strong> {{ $followUp->lead->property->area }}, {{ $followUp->lead->property->city }}</div>
                <div><strong>Price:</strong> ₹{{ number_format($followUp->lead->property->price) }}</div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Buyer Information</h3>
            <div class="space-y-2">
                <div><strong>Name:</strong> {{ $followUp->lead->buyer_name }}</div>
                <div><strong>Email:</strong> {{ $followUp->lead->buyer_email }}</div>
                <div><strong>Phone:</strong> {{ $followUp->lead->buyer_phone }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Follow-Up Details</h3>
            <div class="space-y-2">
                <div><strong>Follow-Up Date:</strong> {{ $followUp->follow_up_date->format('M d, Y H:i') }}</div>
                <div><strong>Status:</strong>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($followUp->status == 'pending') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ ucfirst($followUp->status) }}
                    </span>
                </div>
            </div>
        </div>

        @if($followUp->notes)
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Notes</h3>
            <p class="text-gray-700">{{ $followUp->notes }}</p>
        </div>
        @endif
    </div>

    <div class="border-t pt-4">
        <div class="text-sm text-gray-600">
            <div>Created: {{ $followUp->created_at->format('M d, Y H:i') }}</div>
            <div>Last Updated: {{ $followUp->updated_at->format('M d, Y H:i') }}</div>
        </div>
    </div>
</div>

<div class="mt-6 flex space-x-4">
    <a href="{{ route('agent.follow-ups.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
        Back to Follow-Ups
    </a>
    <a href="{{ route('agent.follow-ups.edit', $followUp) }}" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
        Edit Follow-Up
    </a>
    <a href="{{ route('agent.leads.show', $followUp->lead) }}" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors">
        View Lead
    </a>
</div>
@endsection