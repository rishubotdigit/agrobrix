@extends('layouts.admin.app')

@section('title', 'Lead Details')

@section('content')

<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg rounded-xl p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Lead Inquiry Details</h1>
                <p class="text-blue-100">Comprehensive view of buyer inquiry and property information</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold">#{{ $lead->id }}</div>
                <div class="text-sm text-blue-100">Lead ID</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Inquiry Information -->
            <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Inquiry Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Buyer Name</label>
                                <div class="text-lg font-semibold text-gray-900">{{ $lead->buyer_name }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Buyer Type</label>
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                    @if($lead->buyer_type == 'agent') bg-purple-100 text-purple-800
                                    @elseif($lead->buyer_type == 'buyer') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($lead->buyer_type ?? 'N/A') }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                    @if($lead->status == 'new') bg-blue-100 text-blue-800
                                    @elseif($lead->status == 'contacted') bg-yellow-100 text-yellow-800
                                    @elseif($lead->status == 'qualified') bg-green-100 text-green-800
                                    @elseif($lead->status == 'lost') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($lead->status ?? 'new') }}
                                </span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Email Address</label>
                                <div class="text-lg text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                    {{ $lead->buyer_email }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                                <div class="text-lg text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                    </svg>
                                    {{ $lead->buyer_phone }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($lead->additional_message)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Additional Message</label>
                        <div class="bg-gray-50 rounded-lg p-4 text-gray-700 leading-relaxed">
                            {{ $lead->additional_message }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Property Details -->
            @if($lead->property)
            <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm3 2a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                        </svg>
                        Property Details
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Property Title</label>
                                <div class="text-lg font-semibold text-gray-900">{{ $lead->property->title }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Property ID</label>
                                <div class="text-lg text-gray-900">#{{ $lead->property->id }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Land Type</label>
                                <div class="text-lg text-gray-900">{{ ucfirst($lead->property->land_type ?? 'N/A') }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Area</label>
                                <div class="text-lg text-gray-900">{{ $lead->property->area ? $lead->property->area . ' sq ft' : 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Price</label>
                                <div class="text-2xl font-bold text-green-600">₹{{ number_format($lead->property->price, 2) }}</div>
                                @if($lead->property->price_negotiable)
                                <span class="text-sm text-orange-600 font-medium">Negotiable</span>
                                @endif
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Plot Area</label>
                                <div class="text-lg text-gray-900">
                                    {{ $lead->property->plot_area ? $lead->property->plot_area . ' ' . ($lead->property->plot_area_unit ?? 'sq ft') : 'N/A' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                    @if($lead->property->status == 'approved') bg-green-100 text-green-800
                                    @elseif($lead->property->status == 'rejected') bg-red-100 text-red-800
                                    @elseif($lead->property->status == 'disabled') bg-yellow-100 text-yellow-800
                                    @elseif($lead->property->status == 'canceled') bg-gray-100 text-gray-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($lead->property->status ?? 'pending') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Location</label>
                        <div class="text-lg text-gray-900 flex items-start">
                            <svg class="w-5 h-5 mr-2 text-gray-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                {{ $lead->property->full_address ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    @if($lead->property->description)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Description</label>
                        <div class="bg-gray-50 rounded-lg p-4 text-gray-700 leading-relaxed">
                            {{ Str::limit($lead->property->description, 300) }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Contact Information -->
            <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Contact Details
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($lead->property && $lead->property->owner)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Property Owner</label>
                        <div class="font-semibold text-gray-900">{{ $lead->property->owner->name }}</div>
                        <div class="text-sm text-gray-600">{{ $lead->property->owner->email }}</div>
                        <div class="text-sm text-gray-600">{{ $lead->property->owner->mobile ?? 'N/A' }}</div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1
                            {{ $lead->property->owner->role === 'agent' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($lead->property->owner->role ?? 'owner') }}
                        </span>
                    </div>
                    @endif

                    @if($lead->agent)
                    <div class="pt-4 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Assigned Agent</label>
                        <div class="font-semibold text-gray-900">{{ $lead->agent->name }}</div>
                        <div class="text-sm text-gray-600">{{ $lead->agent->email }}</div>
                        <div class="text-sm text-gray-600">{{ $lead->agent->phone ?? 'N/A' }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-500 to-gray-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        Timeline
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900">Inquiry Created</p>
                            <p class="text-sm text-gray-500">{{ $lead->created_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                    </div>

                    @if($lead->updated_at != $lead->created_at)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900">Last Updated</p>
                            <p class="text-sm text-gray-500">{{ $lead->updated_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white shadow-lg rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.leads.index') }}" class="w-full bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                        Back to Leads
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection