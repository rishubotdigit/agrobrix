@extends('layouts.agent.app')

@section('title', 'Visit Details')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Visit Details</h1>
    <p class="text-gray-600">View visit information and manage status.</p>
</div>

<div class="bg-white shadow-md rounded-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Property Information</h3>
            <div class="space-y-2">
                <div><strong>Title:</strong> {{ $visit->lead->property->title }}</div>
                <div><strong>Location:</strong> {{ $visit->lead->property->area }}, {{ $visit->lead->property->city }}</div>
                <div><strong>Price:</strong> ₹{{ number_format($visit->lead->property->price) }}</div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Buyer Information</h3>
            <div class="space-y-2">
                <div><strong>Name:</strong> {{ $visit->lead->buyer_name }}</div>
                <div><strong>Email:</strong> {{ $visit->lead->buyer_email }}</div>
                <div><strong>Phone:</strong> {{ $visit->lead->buyer_phone }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Visit Details</h3>
            <div class="space-y-2">
                <div><strong>Scheduled At:</strong> {{ $visit->scheduled_at->format('M d, Y H:i') }}</div>
                <div><strong>Status:</strong>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($visit->status == 'scheduled') bg-blue-100 text-blue-800
                        @elseif($visit->status == 'completed') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($visit->status) }}
                    </span>
                </div>
            </div>
        </div>

        @if($visit->notes)
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Notes</h3>
            <p class="text-gray-700">{{ $visit->notes }}</p>
        </div>
        @endif
    </div>

    <div class="border-t pt-4">
        <div class="text-sm text-gray-600">
            <div>Created: {{ $visit->created_at->format('M d, Y H:i') }}</div>
            <div>Last Updated: {{ $visit->updated_at->format('M d, Y H:i') }}</div>
        </div>
    </div>
</div>

<div class="mt-6 flex space-x-4">
    <a href="{{ route('agent.visits.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
        Back to Visits
    </a>
    <a href="{{ route('agent.visits.edit', $visit) }}" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
        Edit Visit
    </a>
    <a href="{{ route('agent.leads.show', $visit->lead) }}" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors">
        View Lead
    </a>
</div>
@endsection