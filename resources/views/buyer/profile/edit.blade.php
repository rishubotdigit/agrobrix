@extends('layouts.user.app')

@section('title', 'Edit Profile')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Profile</h1>
    <p class="text-gray-600">Update your personal information and account settings.</p>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('buyer.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
    @csrf
    @method('PUT')

    <!-- Profile Photo -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
        <div class="flex items-center space-x-4">
            <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="w-full h-full object-cover">
                @else
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                @endif
            </div>
            <div>
                <input type="file" name="profile_photo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-emerald-700">
                <p class="mt-1 text-sm text-gray-500">JPG, PNG, GIF up to 2MB</p>
            </div>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
        </div>

        <div>
            <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
            <input type="tel" name="mobile" id="mobile" value="{{ old('mobile', $user->mobile) }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
        </div>

        <div class="md:col-span-2">
            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
            <textarea name="address" id="address" rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('address', $user->address) }}</textarea>
        </div>
    </div>

    <!-- Password Change -->
    <div class="border-t pt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                <input type="password" name="current_password" id="current_password"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>

            <div></div>

            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input type="password" name="new_password" id="new_password"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>

            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>
        </div>
        <p class="mt-2 text-sm text-gray-500">Leave password fields empty if you don't want to change your password.</p>
    </div>

    <!-- Submit Button -->
    <div class="mt-6 flex justify-end">
        <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
            Update Profile
        </button>
    </div>
</form>
@endsection