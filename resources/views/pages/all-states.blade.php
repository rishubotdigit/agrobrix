@extends('layouts.app')

@section('content')
<div class="py-20 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Explore by State</h1>
            <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                Discover agricultural properties across India's most fertile regions. 
                Browse active listings in your preferred state.
            </p>
        </div>

        @if(isset($states) && $states->isNotEmpty())
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @foreach($states as $stat)
                <a href="{{ route('properties.index', ['state' => $stat->state]) }}" class="stat-card flex flex-col items-center text-center group bg-white rounded-xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer relative overflow-hidden h-64 border border-gray-100">
                    @if($stat->image && \Storage::disk('public')->exists($stat->image))
                         <div class="absolute inset-0 z-0">
                             <img src="{{ asset('storage/' . $stat->image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $stat->state }}">
                             <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent group-hover:via-black/50 transition-colors"></div>
                         </div>
                         <div class="relative z-10 w-full h-full flex flex-col items-center justify-end py-6 px-4">
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center mb-auto mt-4 group-hover:bg-white/30 transition-colors border border-white/40 shadow-inner">
                                @if($stat->icon && \Storage::disk('public')->exists($stat->icon))
                                    <img src="{{ asset('storage/' . $stat->icon) }}" class="w-8 h-8 object-contain filter brightness-0 invert drop-shadow-md" alt="icon">
                                @else
                                    <svg class="w-8 h-8 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="text-center w-full">
                                <h3 class="font-bold text-white text-xl mb-1 shadow-sm tracking-wide">{{ $stat->state }}</h3>
                                <div class="inline-block">
                                    <span class="text-xs text-white font-semibold bg-primary/90 px-3 py-1 rounded-full backdrop-blur-md shadow-sm border border-white/20">
                                        {{ $stat->total }} Properties
                                    </span>
                                </div>
                            </div>
                         </div>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center p-6">
                            <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-4 group-hover:bg-emerald-500 transition-colors duration-300">
                                @if($stat->icon && \Storage::disk('public')->exists($stat->icon))
                                    <img src="{{ asset('storage/' . $stat->icon) }}" class="w-10 h-10 object-contain text-emerald-600 group-hover:text-white transition-all transform group-hover:scale-110" alt="icon">
                                @else
                                    <svg class="w-10 h-10 text-emerald-600 group-hover:text-white transition-all transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                @endif
                            </div>
                            <h3 class="font-bold text-gray-800 text-xl mb-2 group-hover:text-emerald-700 transition-colors">{{ $stat->state }}</h3>
                            <p class="text-sm font-medium text-gray-500">{{ $stat->total }} Properties Available</p>
                        </div>
                    @endif
                </a>
            @endforeach
        </div>
        @else
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No states found</h3>
                <p class="text-gray-500 mt-2">There are currently no active property listings by state.</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" class="text-primary font-medium hover:text-emerald-700">Go back home &rarr;</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
