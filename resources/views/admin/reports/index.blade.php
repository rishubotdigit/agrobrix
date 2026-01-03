@extends('layouts.admin.app')

@section('title', 'Property Reports')

@section('content')
<div class="px-6 py-6 border-b border-gray-200">
    <h1 class="text-2xl font-bold text-gray-900">Property Reports</h1>
</div>

<div class="p-6">
    <!-- Filter Section -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Property</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm" 
                        placeholder="Search by title or ID...">
                </div>
            </div>
            
            <div class="w-full md:w-64">
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Filter by Reason</label>
                <select name="reason" id="reason" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-lg">
                    <option value="">All Reasons</option>
                    <option value="Sold Out" {{ request('reason') == 'Sold Out' ? 'selected' : '' }}>Sold Out</option>
                    <option value="Incorrect Information" {{ request('reason') == 'Incorrect Information' ? 'selected' : '' }}>Incorrect Information</option>
                    <option value="Owner Contact Incorrect" {{ request('reason') == 'Owner Contact Incorrect' ? 'selected' : '' }}>Owner Contact Incorrect</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'reason']))
                    <a href="{{ route('admin.reports.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reported By</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reports as $report)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($report->property->property_images && count(json_decode($report->property->property_images, true)) > 0)
                                    <img class="h-10 w-10 rounded-md object-cover mr-3" src="{{ asset('storage/' . json_decode($report->property->property_images, true)[0]) }}" alt="">
                                @else
                                    <div class="h-10 w-10 rounded-md bg-gray-100 flex items-center justify-center mr-3">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Illuminate\Support\Str::limit($report->property->title, 30) }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        ID: {{ $report->property->id }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                {{ $report->reason }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $report->user->name ?? 'Unknown' }}
                            <div class="text-xs text-gray-400">{{ $report->user->email ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $report->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.reports.show', $report) }}" class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 px-3 py-1 rounded-md">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            No reports found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($reports->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
