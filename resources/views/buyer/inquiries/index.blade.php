@extends('layouts.user.app')

@section('title', 'My Inquiries - Buyer Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">My Inquiries</h1>
    <p class="text-gray-600">Track your submitted property inquiries and their status.</p>
</div>

<!-- Inquiries Table -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Submitted Inquiries</h3>

    @if($inquiries->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buying Purpose</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timeline</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Site Visit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buyer Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Additional Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($inquiries as $inquiry)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $inquiry->property->title }}</div>
                                <div class="text-sm text-gray-500">{{ $inquiry->property->city }}, {{ $inquiry->property->state }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $inquiry->updated_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $inquiry->buying_purpose ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $inquiry->buying_timeline ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $inquiry->interested_in_site_visit ? 'Yes' : 'No' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $inquiry->buyer_type ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $inquiry->additional_message ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($inquiry->status == 'pending')
                                        bg-yellow-100 text-yellow-800
                                    @elseif($inquiry->status == 'contacted')
                                        bg-blue-100 text-blue-800
                                    @elseif($inquiry->status == 'qualified')
                                        bg-purple-100 text-purple-800
                                    @elseif($inquiry->status == 'closed')
                                        bg-red-100 text-red-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($inquiry->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No inquiries yet</h3>
            <p class="mt-1 text-sm text-gray-500">You haven't submitted any property inquiries yet.</p>
            <div class="mt-6">
                <a href="{{ route('buyer.properties') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Browse Properties
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Back to Dashboard -->
<div class="mt-6">
    <a href="{{ route('buyer.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
        <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Dashboard
    </a>
</div>
@endsection