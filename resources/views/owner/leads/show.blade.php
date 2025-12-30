@extends('layouts.owner.app')

@section('title', 'Lead Details')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Lead Details</h1>
    <p class="text-gray-600">View detailed information about this lead.</p>
</div>

<div class="bg-white shadow-md rounded-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Property Information</h3>
            <div class="space-y-2">
                <div><strong>Title:</strong> {{ $lead->property->title }}</div>
                <div><strong>Location:</strong> {{ $lead->property->area }}</div>
                <div><strong>Price:</strong> ₹{{ number_format($lead->property->price) }}</div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Buyer Information</h3>
            <div class="space-y-2">
                <div><strong>Name:</strong> {{ $lead->buyer_name }}</div>
                <div><strong>Email:</strong> {{ $lead->buyer_email }}</div>
                <div><strong>Phone:</strong> {{ $lead->buyer_phone }}</div>
                <div><strong>Type:</strong> {{ ucfirst($lead->buyer_type ?? 'N/A') }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Inquiry Details</h3>
            <div class="space-y-2">
                <div><strong>Buying Purpose:</strong> {{ $lead->buying_purpose ?? 'N/A' }}</div>
                <div><strong>Buying Timeline:</strong> {{ $lead->buying_timeline ?? 'N/A' }}</div>
                <div><strong>Interested in Site Visit:</strong> {{ $lead->interested_in_site_visit ? 'Yes' : 'No' }}</div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Additional Information</h3>
            <div class="space-y-2">
                <div><strong>Status:</strong>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($lead->status == 'pending')
                            bg-yellow-100 text-yellow-800
                        @elseif($lead->status == 'contacted')
                            bg-blue-100 text-blue-800
                        @elseif($lead->status == 'qualified')
                            bg-purple-100 text-purple-800
                        @elseif($lead->status == 'closed')
                            bg-red-100 text-red-800
                        @else
                            bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($lead->status) }}
                    </span>
                </div>
                @if($lead->additional_message)
                <div><strong>Message:</strong> {{ $lead->additional_message }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="border-t pt-4">
        <div class="text-sm text-gray-600">
            <div>Created: {{ $lead->created_at->format('M d, Y H:i') }}</div>
            <div>Last Updated: {{ $lead->updated_at->format('M d, Y H:i') }}</div>
        </div>
    </div>
</div>

<div class="mt-6">
    <a href="{{ route('owner.leads') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
        Back to Leads
    </a>
</div>
@endsection