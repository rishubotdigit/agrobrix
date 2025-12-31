@extends('layouts.app')

@section('content')
<style>
    .hero-section {
        background: linear-gradient(180deg, #d1fae5 0%, #a7f3d0 50%, #6ee7b7 100%);
    }
    .property-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #f3f4f6;
    }
    .property-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.12);
        border-color: #e5e7eb;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        border-color: #10b981;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
        transform: translateY(-2px);
    }
</style>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 hero-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-5xl mx-auto">
                <h1 class="text-3xl md:text-4xl font-bold mb-6 leading-tight">
                    <span class="text-primary">India's Premier Marketplace</span><br>
                    <span class="text-gray-800">for Land & Farms</span>
                </h1>
                
                <p class="text-lg md:text-xl text-gray-700 mb-10 max-w-3xl mx-auto">
                    India's widest selection of managed farmlands, agricultural properties, plantations and rural estates
                </p>

                <!-- Search Form -->
                <div class="bg-white p-4 rounded-2xl shadow-xl max-w-4xl mx-auto">
                    <form action="{{ route('search.advanced') }}" method="GET">
                        <div class="flex flex-col md:flex-row gap-4 items-center">
                            <div class="w-full md:w-5/12">
                                <div class="relative">
                                    <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input type="text" name="q" placeholder="Search by location, type..." class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-primary focus:ring-0 focus:bg-white transition-colors" required>
                                </div>
                            </div>
                            <div class="w-full md:w-2/12">
                                <select name="state_id" id="state_filter" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-primary focus:ring-0 focus:bg-white transition-colors cursor-pointer">
                                    <option value="">State</option>
                                    @foreach(\App\Models\State::orderBy('name')->get() as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full md:w-3/12">
                                <select name="district_id" id="district_filter" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-primary focus:ring-0 focus:bg-white transition-colors cursor-pointer">
                                    <option value="">District</option>
                                </select>
                            </div>
                            <div class="w-full md:w-2/12">
                                <button type="submit" class="w-full bg-primary text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-dark transition shadow-md">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Dynamic district loading
        document.getElementById('state_filter').addEventListener('change', function() {
            const stateId = this.value;
            const districtSelect = document.getElementById('district_filter');
            
            districtSelect.innerHTML = '<option value="">District</option>';
            
            if (stateId) {
                fetch(`/api/districts/${stateId}`)
                    .then(response => response.json())
                    .then(districts => {
                        districts.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district.id;
                            option.textContent = district.name;
                            districtSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error loading districts:', error));
            }
        });
    </script>

    <!-- 1. Featured Properties Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Featured Properties</h2>
                    <p class="text-gray-600">Handpicked premium properties for you</p>
                </div>
                <a href="{{ route('search.advanced', ['sort' => 'featured']) }}" class="text-primary hover:text-primary-dark font-semibold flex items-center group">
                    View All
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($featuredProperties as $property)
                    <x-property-card-simple :property="$property" />
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No featured properties available.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 2. Latest Properties Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Latest Properties</h2>
                    <p class="text-gray-600">Newest additions to our marketplace</p>
                </div>
                <a href="{{ route('search.advanced', ['sort' => 'newest']) }}" class="text-primary hover:text-primary-dark font-semibold flex items-center group">
                    View All
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($latestProperties as $property)
                    <x-property-card-simple :property="$property" />
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No latest properties available.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 3. Properties in Selected State -->
    @if(isset($selectedStateProperties) && $selectedStateProperties->isNotEmpty())
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Properties in {{ $selectedState }}</h2>
                    <p class="text-gray-600">Local opportunities in your region</p>
                </div>
                <a href="{{ route('properties.index', ['state' => $selectedState]) }}" class="text-primary hover:text-primary-dark font-semibold flex items-center group">
                    View All
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($selectedStateProperties as $property)
                    <x-property-card-simple :property="$property" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- 4. Properties by State (Summary) -->
    @if(isset($stateSummary) && $stateSummary->isNotEmpty())
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Browse by State</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($stateSummary as $stat)
                    <a href="{{ route('properties.index', ['state' => $stat->state]) }}" class="stat-card flex flex-col items-center text-center group bg-gray-50 hover:bg-white cursor-pointer">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-emerald-500 transition-colors">
                            <svg class="w-6 h-6 text-emerald-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">{{ $stat->state }}</h3>
                        <p class="text-sm text-gray-500">{{ $stat->total }} Properties</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- 5. Properties by Category (Summary) -->
    @if(isset($categorySummary) && $categorySummary->isNotEmpty())
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Browse by Category</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($categorySummary as $cat)
                    <a href="{{ route('search.advanced', ['type' => $cat->land_type]) }}" class="stat-card flex items-center p-4 hover:bg-white cursor-pointer group">
                        <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-blue-600 transition-colors">
                            <svg class="w-7 h-7 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900">{{ ucfirst($cat->land_type) }}</h3>
                            <p class="text-sm text-gray-500">{{ $cat->total }} Properties</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

@include('components.contact-inquiry-modal')

@endsection