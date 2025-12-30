@extends('layouts.admin.app')

@section('title', 'Edit User')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit User: {{ $user->name }}</h1>

    <div class="bg-white shadow-md rounded-lg p-8">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Display Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror" required>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">User Role</label>
                        <select name="role" id="role" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary @error('role') border-red-500 @enderror" required>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="owner" {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>Owner</option>
                            <option value="agent" {{ old('role', $user->role) == 'agent' ? 'selected' : '' }}>Agent</option>
                            <option value="buyer" {{ old('role', $user->role) == 'buyer' ? 'selected' : '' }}>Buyer</option>
                        </select>
                        @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                        <div class="flex items-center space-x-4">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" class="h-16 w-16 rounded-full object-cover">
                            @else
                                <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xl uppercase">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                            <input type="file" name="profile_photo" id="profile_photo" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:opacity-90">
                        </div>
                        @error('profile_photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="plan_id" class="block text-sm font-medium text-gray-700 mb-1">Assigned Plan</label>
                        <select name="plan_id" id="plan_id" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary @error('plan_id') border-red-500 @enderror">
                            <option value="">No Active Plan</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ old('plan_id', $activePlanId) == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} ({{ ucfirst($plan->role) }}) - ₹{{ number_format($plan->price, 0) }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Assigning a new plan will deactivate the current one.</p>
                        @error('plan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 border-t pt-6">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2 border rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-semibold">Save Changes</button>
            </div>
        </form>
    </div>
@endsection