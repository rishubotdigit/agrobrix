@extends('layouts.admin.app')

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

<form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
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

<!-- Delete Account Section -->
<div class="mt-8 bg-white p-6 rounded-xl shadow-sm border border-red-200">
    <h3 class="text-lg font-medium text-red-600 mb-4">Danger Zone</h3>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-700 font-medium">Delete Account</p>
            <p class="text-sm text-gray-500">Permanently delete your account and all associated data.</p>
        </div>
        <button type="button" onclick="openDeleteModal()" class="bg-red-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-red-700 transition-colors">
            Delete Account
        </button>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-xl">
        <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Delete Account</h3>
            <p class="text-gray-600 mb-2">Are you sure you want to delete your account?</p>
            <p class="text-sm text-gray-500 mb-6">Your account will be marked for deletion. If you don't log in within <strong>24 hours</strong>, your account will be permanently deleted. You can cancel this by logging back in.</p>
            
            <div class="flex space-x-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <form action="{{ route('admin.profile.delete') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">
                        Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection