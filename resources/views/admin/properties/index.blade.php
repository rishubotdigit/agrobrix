@extends('layouts.admin.app')

@section('title', 'Properties')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Properties Management</h1>
            <p class="text-gray-600">Manage all properties in the system.</p>
        </div>
        <a href="{{ route('admin.properties.create') }}" class="bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Create Property
        </a>
    </div>
</div>

<!-- Filters and View Toggle -->
<div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-900">Filters</h2>
        <div class="flex items-center space-x-4">
            <!-- View Toggle -->
            <div class="flex items-center bg-gray-100 rounded-lg p-1">
                <a href="{{ request()->fullUrlWithQuery(['view_mode' => 'grid']) }}"
                   class="px-3 py-2 rounded-md text-sm font-medium {{ $viewMode == 'grid' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Grid
                </a>
                <a href="{{ request()->fullUrlWithQuery(['view_mode' => 'list']) }}"
                   class="px-3 py-2 rounded-md text-sm font-medium {{ $viewMode == 'list' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    List
                </a>
            </div>
            <button type="button" onclick="toggleFilters()" class="text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.properties.index') }}" id="filterForm" class="space-y-4" style="display: none;">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    <option value="">All Status</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="disabled" {{ request('status') == 'disabled' ? 'selected' : '' }}>Disabled</option>
                </select>
            </div>

            <!-- State -->
            <div>
                <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State</label>
                <select name="state" id="state" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    <option value="">All States</option>
                    @foreach($states as $state)
                        <option value="{{ $state->name }}" {{ request('state') == $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- District -->
            <div>
                <label for="district" class="block text-sm font-medium text-gray-700 mb-1">District</label>
                <select name="district" id="district" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    <option value="">All Districts</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->name }}" {{ request('district') == $district->name ? 'selected' : '' }}>{{ $district->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" name="title" id="title" value="{{ request('title') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Search by title">
            </div>

            <!-- Price Min -->
            <div>
                <label for="price_min" class="block text-sm font-medium text-gray-700 mb-1">Min Price</label>
                <input type="number" name="price_min" id="price_min" value="{{ request('price_min') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="0">
            </div>

            <!-- Price Max -->
            <div>
                <label for="price_max" class="block text-sm font-medium text-gray-700 mb-1">Max Price</label>
                <input type="number" name="price_max" id="price_max" value="{{ request('price_max') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="1000000">
            </div>

            <!-- Area Min -->
            <div>
                <label for="area_min" class="block text-sm font-medium text-gray-700 mb-1">Min Area</label>
                <input type="number" name="area_min" id="area_min" value="{{ request('area_min') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="0">
            </div>

            <!-- Area Max -->
            <div>
                <label for="area_max" class="block text-sm font-medium text-gray-700 mb-1">Max Area</label>
                <input type="number" name="area_max" id="area_max" value="{{ request('area_max') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="10000">
            </div>

            <!-- Created From -->
            <div>
                <label for="created_from" class="block text-sm font-medium text-gray-700 mb-1">Created From</label>
                <input type="date" name="created_from" id="created_from" value="{{ request('created_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
            </div>

            <!-- Created To -->
            <div>
                <label for="created_to" class="block text-sm font-medium text-gray-700 mb-1">Created To</label>
                <input type="date" name="created_to" id="created_to" value="{{ request('created_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
            </div>

            <!-- Owner Name -->
            <div>
                <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-1">Owner Name</label>
                <input type="text" name="owner_name" id="owner_name" value="{{ request('owner_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Search by owner name">
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.properties.index') }}" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Clear Filters</a>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-emerald-700">Apply Filters</button>
        </div>
    </form>
</div>

<!-- Your Properties Section -->
<div class="mb-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Your Properties</h2>
    @if($myProperties->count() > 0)
    @if($viewMode == 'grid')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($myProperties as $property)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <!-- Property Image -->
            <div class="relative h-48 bg-gray-200">
                @if($property->property_images && is_array(json_decode($property->property_images, true)))
                    @php $images = json_decode($property->property_images, true); @endphp
                    @if(count($images) > 0)
                        <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    @endif
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                @endif

                <!-- Status Badge -->
                <div class="absolute top-3 right-3">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($property->status == 'approved') bg-green-100 text-green-800
                        @elseif($property->status == 'rejected') bg-red-100 text-red-800
                        @elseif($property->status == 'disabled') bg-yellow-100 text-yellow-800
                        @elseif($property->status == 'canceled') bg-gray-100 text-gray-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($property->status ?? 'pending') }}
                    </span>
                </div>
            </div>

            <!-- Property Details -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2 overflow-hidden" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $property->title }}</h3>

                <div class="flex items-center text-gray-600 mb-3">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $property->area }}
                </div>

                <div class="flex items-center justify-between mb-4">
                    <div class="text-2xl font-bold text-primary">₹{{ number_format($property->price) }}</div>
                    @if($property->plot_area)
                        <div class="text-sm text-gray-600">{{ $property->plot_area }} {{ $property->plot_area_unit }}</div>
                    @endif
                </div>

                <!-- Owner Info -->
                <div class="mb-4 pb-4 border-b border-gray-200">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Owner: {{ $property->owner->name }}
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.properties.show', $property) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors text-center text-sm">
                        View
                    </a>
                    <a href="{{ route('admin.properties.versions', $property) }}" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-medium hover:bg-blue-200 transition-colors text-center text-sm">
                        Versions
                    </a>
                    @if($property->status == 'approved')
                        <form method="POST" action="{{ route('admin.properties.disable', $property) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg font-medium hover:bg-yellow-200 transition-colors text-sm">
                                Disable
                            </button>
                        </form>
                        <a href="{{ route('admin.properties.edit', $property) }}" class="bg-purple-100 text-purple-700 px-4 py-2 rounded-lg font-medium hover:bg-purple-200 transition-colors text-center text-sm">
                            Edit
                        </a>
                    @elseif($property->status == 'rejected')
                        <form method="POST" action="{{ route('admin.properties.re-approve', $property) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-100 text-green-700 px-4 py-2 rounded-lg font-medium hover:bg-green-200 transition-colors text-sm">
                                Re-Approve
                            </button>
                        </form>
                    @elseif($property->status == 'canceled')
                        <form method="POST" action="{{ route('admin.properties.re-enable', $property) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-medium hover:bg-blue-200 transition-colors text-sm">
                                Re-Enable
                            </button>
                        </form>
                    @endif
                    <button onclick="deleteProperty({{ $property->id }})" class="bg-red-100 text-red-700 px-4 py-2 rounded-lg font-medium hover:bg-red-200 transition-colors text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- List View -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Area</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($myProperties as $property)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden">
                                @if($property->property_images && is_array(json_decode($property->property_images, true)))
                                    @php $images = json_decode($property->property_images, true); @endphp
                                    @if(count($images) > 0)
                                        <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                    @endif
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 truncate max-w-xs">{{ $property->title }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $property->area }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-primary">₹{{ number_format($property->price) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">
                                @if($property->plot_area)
                                    {{ $property->plot_area }} {{ $property->plot_area_unit }}
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $property->owner->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($property->status == 'approved') bg-green-100 text-green-800
                                @elseif($property->status == 'rejected') bg-red-100 text-red-800
                                @elseif($property->status == 'disabled') bg-yellow-100 text-yellow-800
                                @elseif($property->status == 'canceled') bg-gray-100 text-gray-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($property->status ?? 'pending') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $property->created_at->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.properties.show', $property) }}" class="text-gray-600 hover:text-gray-900">View</a>
                                <a href="{{ route('admin.properties.versions', $property) }}" class="text-blue-600 hover:text-blue-900">Versions</a>
                                @if($property->status == 'approved')
                                    <form method="POST" action="{{ route('admin.properties.disable', $property) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900">Disable</button>
                                    </form>
                                    <a href="{{ route('admin.properties.edit', $property) }}" class="text-purple-600 hover:text-purple-900">Edit</a>
                                @elseif($property->status == 'rejected')
                                    <form method="POST" action="{{ route('admin.properties.re-approve', $property) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900">Re-Approve</button>
                                    </form>
                                @elseif($property->status == 'canceled')
                                    <form method="POST" action="{{ route('admin.properties.re-enable', $property) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-900">Re-Enable</button>
                                    </form>
                                @endif
                                <button onclick="deleteProperty({{ $property->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Pagination -->
    <div class="mt-8">
        {{ $myProperties->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        <h3 class="text-xl font-medium text-gray-900 mb-2">No properties found</h3>
        <p class="text-gray-600">You haven't created any properties yet.</p>
    </div>
    @endif
</div>

<!-- All Properties Section -->
<div class="mb-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">All Properties</h2>
    @if($properties->count() > 0)
    @if($viewMode == 'grid')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($properties as $property)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <!-- Property Image -->
            <div class="relative h-48 bg-gray-200">
                @if($property->property_images && is_array(json_decode($property->property_images, true)))
                    @php $images = json_decode($property->property_images, true); @endphp
                    @if(count($images) > 0)
                        <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    @endif
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                @endif

                <!-- Status Badge -->
                <div class="absolute top-3 right-3">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($property->status == 'approved') bg-green-100 text-green-800
                        @elseif($property->status == 'rejected') bg-red-100 text-red-800
                        @elseif($property->status == 'disabled') bg-yellow-100 text-yellow-800
                        @elseif($property->status == 'canceled') bg-gray-100 text-gray-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($property->status ?? 'pending') }}
                    </span>
                </div>
            </div>

            <!-- Property Details -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2 overflow-hidden" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $property->title }}</h3>

                <div class="flex items-center text-gray-600 mb-3">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $property->area }}
                </div>

                <div class="flex items-center justify-between mb-4">
                    <div class="text-2xl font-bold text-primary">₹{{ number_format($property->price) }}</div>
                    @if($property->plot_area)
                        <div class="text-sm text-gray-600">{{ $property->plot_area }} {{ $property->plot_area_unit }}</div>
                    @endif
                </div>

                <!-- Owner Info -->
                <div class="mb-4 pb-4 border-b border-gray-200">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Owner: {{ $property->owner->name }}
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.properties.show', $property) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors text-center text-sm">
                        View
                    </a>
                    <a href="{{ route('admin.properties.versions', $property) }}" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-medium hover:bg-blue-200 transition-colors text-center text-sm">
                        Versions
                    </a>
                    @if($property->status == 'approved')
                        <form method="POST" action="{{ route('admin.properties.disable', $property) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg font-medium hover:bg-yellow-200 transition-colors text-sm">
                                Disable
                            </button>
                        </form>
                        <a href="{{ route('admin.properties.edit', $property) }}" class="bg-purple-100 text-purple-700 px-4 py-2 rounded-lg font-medium hover:bg-purple-200 transition-colors text-center text-sm">
                            Edit
                        </a>
                    @elseif($property->status == 'rejected')
                        <form method="POST" action="{{ route('admin.properties.re-approve', $property) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-100 text-green-700 px-4 py-2 rounded-lg font-medium hover:bg-green-200 transition-colors text-sm">
                                Re-Approve
                            </button>
                        </form>
                    @elseif($property->status == 'canceled')
                        <form method="POST" action="{{ route('admin.properties.re-enable', $property) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-medium hover:bg-blue-200 transition-colors text-sm">
                                Re-Enable
                            </button>
                        </form>
                    @endif
                    <button onclick="deleteProperty({{ $property->id }})" class="bg-red-100 text-red-700 px-4 py-2 rounded-lg font-medium hover:bg-red-200 transition-colors text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- List View -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Area</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($properties as $property)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden">
                                @if($property->property_images && is_array(json_decode($property->property_images, true)))
                                    @php $images = json_decode($property->property_images, true); @endphp
                                    @if(count($images) > 0)
                                        <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                    @endif
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 truncate max-w-xs">{{ $property->title }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $property->area }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-primary">₹{{ number_format($property->price) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">
                                @if($property->plot_area)
                                    {{ $property->plot_area }} {{ $property->plot_area_unit }}
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $property->owner->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($property->status == 'approved') bg-green-100 text-green-800
                                @elseif($property->status == 'rejected') bg-red-100 text-red-800
                                @elseif($property->status == 'disabled') bg-yellow-100 text-yellow-800
                                @elseif($property->status == 'canceled') bg-gray-100 text-gray-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($property->status ?? 'pending') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $property->created_at->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.properties.show', $property) }}" class="text-gray-600 hover:text-gray-900">View</a>
                                <a href="{{ route('admin.properties.versions', $property) }}" class="text-blue-600 hover:text-blue-900">Versions</a>
                                @if($property->status == 'approved')
                                    <form method="POST" action="{{ route('admin.properties.disable', $property) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900">Disable</button>
                                    </form>
                                    <a href="{{ route('admin.properties.edit', $property) }}" class="text-purple-600 hover:text-purple-900">Edit</a>
                                @elseif($property->status == 'rejected')
                                    <form method="POST" action="{{ route('admin.properties.re-approve', $property) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900">Re-Approve</button>
                                    </form>
                                @elseif($property->status == 'canceled')
                                    <form method="POST" action="{{ route('admin.properties.re-enable', $property) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-900">Re-Enable</button>
                                    </form>
                                @endif
                                <button onclick="deleteProperty({{ $property->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Pagination -->
    <div class="mt-8">
        {{ $properties->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        <h3 class="text-xl font-medium text-gray-900 mb-2">No properties found</h3>
        <p class="text-gray-600">There are no properties in the system yet.</p>
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Property</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to delete this property? This action cannot be undone.</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Delete Property
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function deleteProperty(id) {
    document.getElementById('deleteForm').action = `/admin/properties/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function toggleFilters() {
    const filterForm = document.getElementById('filterForm');
    filterForm.style.display = filterForm.style.display === 'none' ? 'block' : 'none';
}
</script>
@endsection