@extends('layouts.auth')

@section('title', 'Role Selection - Agrobrix')

@section('content')
<div class="bg-white/80 backdrop-blur-lg py-10 px-4 sm:px-6 md:px-8 lg:px-12 shadow-2xl rounded-3xl border border-white/20 w-full">
    <!-- Logo/Brand Section -->
    <div class="text-center mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Choose Your Role</h2>
        <p class="text-gray-600">Select the role that best describes you</p>
    </div>

    <!-- Alert Messages -->
    @if(session('message'))
        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ session('message') }}
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Role Selection Form -->
    <form action="{{ route('auth.role.selection.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="space-y-4">
            <div class="flex items-center">
                <input id="buyer" name="role" type="radio" value="buyer" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300" checked>
                <label for="buyer" class="ml-3 block text-sm font-medium text-gray-700">
                    <span class="font-semibold">Buyer</span> - Looking to purchase properties
                </label>
            </div>

            <div class="flex items-center">
                <input id="agent" name="role" type="radio" value="agent" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                <label for="agent" class="ml-3 block text-sm font-medium text-gray-700">
                    <span class="font-semibold">Agent</span> - Help buyers find properties
                </label>
            </div>

            <div class="flex items-center">
                <input id="owner" name="role" type="radio" value="owner" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                <label for="owner" class="ml-3 block text-sm font-medium text-gray-700">
                    <span class="font-semibold">Owner</span> - List and manage your properties
                </label>
            </div>
        </div>

        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-base font-semibold text-black bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 transform hover:scale-[1.02]">
            <span class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Continue
            </span>
        </button>
    </form>
</div>
@endsection