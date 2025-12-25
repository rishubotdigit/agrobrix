@extends('layouts.admin.app')

@section('title', 'User Details')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">User Details</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <strong>ID:</strong> {{ $user->id }}
            </div>
            <div>
                <strong>Name:</strong> {{ $user->name }}
            </div>
            <div>
                <strong>Email:</strong> {{ $user->email }}
            </div>
            <div>
                <strong>Mobile:</strong> {{ $user->mobile }}
            </div>
            <div>
                <strong>Role:</strong> {{ $user->role }}
            </div>
            <div>
                <strong>Verified:</strong> {{ $user->isVerified() ? 'Yes' : 'No' }}
            </div>
            <div>
                <strong>Created At:</strong> {{ $user->created_at->format('Y-m-d H:i:s') }}
            </div>
            <div>
                <strong>Updated At:</strong> {{ $user->updated_at->format('Y-m-d H:i:s') }}
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back to Users</a>
            <a href="{{ route('admin.users.plans', $user) }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 ml-2">View Plans</a>
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 ml-2">Edit User</a>
            @if($user->role !== 'admin' && !$is_impersonating)
                <form method="POST" action="{{ route('admin.users.impersonate', $user) }}" class="inline ml-2">
                    @csrf
                    <button type="submit" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">Login as User</button>
                </form>
            @endif
        </div>
    </div>
@endsection