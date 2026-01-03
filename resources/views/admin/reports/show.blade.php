@extends('layouts.admin.app')

@section('title', 'Report Details')

@section('content')
<div class="px-6 py-6 border-b border-gray-200 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Report Details</h1>
        <p class="text-sm text-gray-500 mt-1">Report ID: #{{ $report->id }}</p>
    </div>
    <a href="{{ route('admin.reports.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center gap-1">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Reports
    </a>
</div>

<div class="p-6 max-w-4xl">
    
    <!-- Report Info -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Report Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 mb-1">Reason</p>
                <p class="text-base font-semibold text-red-600">{{ $report->reason }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Date & Time</p>
                <p class="text-base font-medium text-gray-900">{{ $report->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500 mb-1">Additional Details</p>
                <div class="bg-gray-50 p-4 rounded-lg text-gray-700 whitespace-pre-wrap">{{ $report->details ?? 'No additional details provided.' }}</div>
            </div>
        </div>
    </div>

    <!-- Reported Property -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-2">
            <h2 class="text-lg font-bold text-gray-900">Reported Property</h2>
            <a href="{{ route('properties.show', $report->property) }}" target="_blank" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium flex items-center gap-1">
                View Property
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </div>
        
        <div class="flex items-start gap-4">
            @if($report->property->property_images && count(json_decode($report->property->property_images, true)) > 0)
                <img class="w-24 h-24 rounded-lg object-cover" src="{{ asset('storage/' . json_decode($report->property->property_images, true)[0]) }}" alt="">
            @endif
            <div>
                <h3 class="text-lg font-bold text-gray-900">{{ $report->property->title }}</h3>
                <p class="text-gray-500 text-sm mt-1">{{ $report->property->full_address }}</p>
                <p class="text-emerald-600 font-bold mt-2">{!! format_indian_currency($report->property->price) !!}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Reporter Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Reported By</h2>
            <div class="flex items-center gap-3">
                @if($report->user->profile_photo)
                    <img src="{{ asset('storage/' . $report->user->profile_photo) }}" class="w-12 h-12 rounded-full object-cover">
                @else
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 font-bold text-lg">
                        {{ substr($report->user->name, 0, 1) }}
                    </div>
                @endif
                <div>
                    <p class="font-bold text-gray-900">{{ $report->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $report->user->email }}</p>
                    <p class="text-sm text-gray-500">{{ $report->user->mobile }}</p>
                </div>
            </div>
        </div>

        <!-- Property Owner Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Property Owner / Agent</h2>
            <div class="flex items-center gap-3">
                @if($report->property->owner->profile_photo)
                    <img src="{{ asset('storage/' . $report->property->owner->profile_photo) }}" class="w-12 h-12 rounded-full object-cover">
                @else
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 font-bold text-lg">
                        {{ substr($report->property->owner->name, 0, 1) }}
                    </div>
                @endif
                <div>
                    <p class="font-bold text-gray-900">{{ $report->property->owner->name }}</p>
                    <p class="text-sm text-gray-500">{{ $report->property->owner->email }}</p>
                    <p class="text-sm text-gray-500">{{ $report->property->owner->mobile }}</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
