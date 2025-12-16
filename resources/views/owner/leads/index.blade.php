@extends('layouts.owner.app')

@section('title', 'Leads - Owner Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Property Leads</h1>
    <p class="text-gray-600">View and manage inquiries for your properties.</p>
</div>

<!-- Leads Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">All Leads</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buyer Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buying Purpose</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timeline</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Site Visit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Additional Message</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($leads as $lead)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $lead->property->title }}</div>
                            <div class="text-sm text-gray-500">{{ $lead->property->city }}, {{ $lead->property->state }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $lead->buyer_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $lead->buyer_email }}</div>
                            <div class="text-sm text-gray-500">{{ $lead->buyer_phone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $lead->buying_purpose ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $lead->buying_timeline ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $lead->interested_in_site_visit ? 'Yes' : 'No' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $lead->additional_message ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $lead->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('owner.leads.show', $lead->id) }}" class="text-primary hover:text-emerald-700">View</a>
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="11" class="px-6 py-4 text-center text-sm text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-lg font-medium">No leads found</p>
                        <p class="text-sm">You haven't received any leads yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Back to Dashboard -->
<div class="mt-8">
    <a href="{{ route('owner.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors">
        <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Dashboard
    </a>
</div>
    
@endsection