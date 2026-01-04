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
    .search-dropdown {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1.25rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .search-dropdown:hover {
        border-color: #10b981;
        background-color: #f0fdf4;
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
                <div class="bg-white p-4 md:p-6 rounded-2xl shadow-xl max-w-7xl mx-auto -mt-6 relative z-20 border border-gray-100">
                    <form action="{{ route('search.advanced') }}" method="GET" id="search-form">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 items-end">
                            <!-- State -->
                            <div class="flex flex-col">
                                <label class="text-xs font-bold text-gray-500 mb-1.5 ml-1 uppercase tracking-wider">State</label>
                                <select name="state_id" id="state_filter" class="search-dropdown w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-primary focus:ring-0 focus:bg-white transition-all cursor-pointer text-gray-700">
                                    <option value="">All States</option>
                                    @foreach(\App\Models\State::orderBy('name')->get() as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- District -->
                            <div class="flex flex-col">
                                <label class="text-xs font-bold text-gray-500 mb-1.5 ml-1 uppercase tracking-wider">District</label>
                                <select name="district_id" id="district_filter" class="search-dropdown w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-primary focus:ring-0 focus:bg-white transition-all cursor-pointer text-gray-700">
                                    <option value="">All Districts</option>
                                </select>
                            </div>
                            <!-- Amenities (Main) -->
                            <div class="flex flex-col">
                                <label class="text-xs font-bold text-gray-500 mb-1.5 ml-1 uppercase tracking-wider">Categories</label>
                                <select name="category_id" id="category_filter" class="search-dropdown w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-primary focus:ring-0 focus:bg-white transition-all cursor-pointer text-gray-700">
                                    <option value="">All Categorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Land Area -->
                            <div class="flex flex-col">
                                <label class="text-xs font-bold text-gray-500 mb-1.5 ml-1 uppercase tracking-wider">Land Area</label>
                                <select name="area_range" id="area_filter" class="search-dropdown w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-primary focus:ring-0 focus:bg-white transition-all cursor-pointer text-gray-700">
                                    <option value="">All Areas</option>
                                    <option value="0-1">Under 1 Acre</option>
                                    <option value="1-5">1 - 5 Acres</option>
                                    <option value="5-10">5 - 10 Acres</option>
                                    <option value="10-20">10 - 20 Acres</option>
                                    <option value="20-plus">20+ Acres</option>
                                </select>
                            </div>
                            <!-- Price -->
                            <div class="flex flex-col">
                                <label class="text-xs font-bold text-gray-500 mb-1.5 ml-1 uppercase tracking-wider">Price Range</label>
                                <select name="price_range" id="price_filter" class="search-dropdown w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-primary focus:ring-0 focus:bg-white transition-all cursor-pointer text-gray-700">
                                    <option value="">All Prices</option>
                                    <option value="0-1000000">Under 10L</option>
                                    <option value="1000000-5000000">10L - 50L</option>
                                    <option value="5000000-10000000">50L - 1Cr</option>
                                    <option value="10000000-50000000">1Cr - 5Cr</option>
                                    <option value="50000000-plus">5Cr+</option>
                                </select>
                            </div>
                            <!-- Search Button -->
                            <div class="flex flex-col">
                                <button type="submit" class="w-full bg-primary text-white px-2 py-3 rounded-xl font-bold hover:bg-primary-dark transition-all shadow-lg hover:shadow-primary/30 flex items-center justify-center gap-2 h-[52px]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    Search
                                </button>
                            </div>
                        </div>
                        <div id="search-error" class="hidden mt-4 text-sm bg-red-50 text-red-600 px-4 py-2 rounded-lg border border-red-100 text-center font-medium animate-pulse">
                            Please select at least one filter to begin your search.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stateSelect = document.getElementById('state_filter');
            const districtSelect = document.getElementById('district_filter');
            const searchForm = document.getElementById('search-form');
            const searchError = document.getElementById('search-error');
            const allDropdowns = document.querySelectorAll('.search-dropdown');

            // Dynamic district loading
            stateSelect.addEventListener('change', function() {
                const stateId = this.value;
                districtSelect.innerHTML = '<option value="">All Districts</option>';
                
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
                            // Update visual for district select as it resets
                            updateVisuals(districtSelect);
                        })
                        .catch(error => console.error('Error loading districts:', error));
                }
            });

            // Visual feedback on selection
            function updateVisuals(el) {
                if (el.value) {
                    el.classList.add('!bg-emerald-50', '!border-emerald-300', '!text-emerald-700', 'font-semibold');
                    el.classList.remove('bg-gray-50', 'border-gray-200');
                } else {
                    el.classList.remove('!bg-emerald-50', '!border-emerald-300', '!text-emerald-700', 'font-semibold');
                    el.classList.add('bg-gray-50', 'border-gray-200');
                }
            }

            allDropdowns.forEach(dropdown => {
                // Initialize visuals (for back button/refresh)
                updateVisuals(dropdown);
                
                dropdown.addEventListener('change', () => {
                    updateVisuals(dropdown);
                    searchError.classList.add('hidden');
                });
            });

            // Validation
            searchForm.addEventListener('submit', function(e) {
                let selectedCount = 0;
                allDropdowns.forEach(dropdown => {
                    if (dropdown.value) selectedCount++;
                });

                if (selectedCount === 0) {
                    e.preventDefault();
                    searchError.classList.remove('hidden');
                    searchError.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    
                    // Hide message after 5 seconds
                    setTimeout(() => {
                        searchError.classList.add('hidden');
                    }, 5000);
                }
            });
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

    <!-- 3. Properties by Selected States (Dynamic Sections) -->
    @if(isset($statePropertiesCollection) && count($statePropertiesCollection) > 0)
        @foreach($statePropertiesCollection as $index => $stateData)
            <section class="py-16 {{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-end mb-10">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Properties in {{ $stateData['state'] }}</h2>
                            <p class="text-gray-600">Local opportunities in {{ $stateData['state'] }}</p>
                        </div>
                        <a href="{{ route('search.advanced', ['state' => $stateData['state']]) }}" class="text-primary hover:text-primary-dark font-semibold flex items-center group">
                            View All
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($stateData['properties'] as $property)
                            <x-property-card-simple :property="$property" />
                        @endforeach
                    </div>
                </div>
            </section>
        @endforeach
    @endif

    <!-- 4. Properties by State (Summary) -->
    @if(isset($stateSummary) && $stateSummary->isNotEmpty())
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Browse by State</h2>
                <a href="{{ route('states.all') }}" class="text-primary hover:text-primary-dark font-semibold flex items-center group">
                    View All
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($stateSummary as $stat)
                    <a href="{{ route('search.advanced', ['state' => $stat->state]) }}" class="stat-card flex flex-col items-center text-center group bg-gray-50 hover:bg-white cursor-pointer relative overflow-hidden">
                        @if($stat->image && \Storage::disk('public')->exists($stat->image))
                             <div class="absolute inset-0 z-0">
                                 <img src="{{ asset('storage/' . $stat->image) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="{{ $stat->state }}">
                                 <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors"></div>
                             </div>
                             <div class="relative z-10 w-full h-full flex flex-col items-center justify-center py-4">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mb-3 group-hover:bg-white/30 transition-colors border border-white/30">
                                    @if($stat->icon && \Storage::disk('public')->exists($stat->icon))
                                        <img src="{{ asset('storage/' . $stat->icon) }}" class="w-6 h-6 object-contain filter brightness-0 invert" alt="icon">
                                    @else
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    @endif
                                </div>
                                <h3 class="font-bold text-white text-lg mb-1 shadow-sm">{{ $stat->state }}</h3>
                                <p class="text-xs text-white/90 font-medium bg-black/30 px-2 py-0.5 rounded-full backdrop-blur-md">{{ $stat->total }} Properties</p>
                             </div>
                        @else
                            <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-emerald-500 transition-colors">
                                @if($stat->icon && \Storage::disk('public')->exists($stat->icon))
                                    <img src="{{ asset('storage/' . $stat->icon) }}" class="w-6 h-6 object-contain text-emerald-600 group-hover:text-white transition-all" alt="icon">
                                @else
                                    <svg class="w-6 h-6 text-emerald-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                @endif
                            </div>
                            <h3 class="font-bold text-gray-900 mb-1">{{ $stat->state }}</h3>
                            <p class="text-sm text-gray-500">{{ $stat->total }} Properties</p>
                        @endif
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

<script>
    function toggleWishlist(propertyId, buttonElement) {
        const heartIcon = buttonElement.querySelector('svg');
        const isInWishlist = heartIcon.classList.contains('fill-current');

        const url = isInWishlist ? `/wishlist/remove/${propertyId}` : '/wishlist/add';
        const method = isInWishlist ? 'DELETE' : 'POST';
        const body = isInWishlist ? null : JSON.stringify({ property_id: propertyId });

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
            },
            body: body
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (isInWishlist) {
                    heartIcon.classList.remove('text-red-500', 'fill-current');
                    heartIcon.classList.add('text-gray-600');
                    heartIcon.setAttribute('fill', 'none');
                } else {
                    heartIcon.classList.remove('text-gray-600');
                    heartIcon.classList.add('text-red-500', 'fill-current');
                    heartIcon.setAttribute('fill', 'currentColor');
                }
            } else {
                alert(data.error || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error toggling wishlist:', error);
            alert('An error occurred while updating wishlist');
        });
    }
</script>

@endsection