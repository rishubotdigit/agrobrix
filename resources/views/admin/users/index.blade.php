@extends('layouts.admin.app')

@section('title', 'Users')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Users Management</h1>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, mobile..." class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                        <option value="agent" {{ request('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                        <option value="buyer" {{ request('role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="verified" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">All Status</option>
                        <option value="yes" {{ request('verified') == 'yes' ? 'selected' : '' }}>Verified</option>
                        <option value="no" {{ request('verified') == 'no' ? 'selected' : '' }}>Unverified</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-primary focus:border-primary">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark transition">Apply Filters</button>
                @if(request()->hasAny(['search', 'role', 'verified', 'date_from', 'date_to']))
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition text-center">Clear All</a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($user->profile_photo)
                                <img class="h-10 w-10 rounded-full mr-3" src="{{ asset('storage/' . $user->profile_photo) }}" alt="">
                            @else
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3 text-gray-500 font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 uppercase">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($user->isVerified())
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Verified
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Unverified
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            
                            <form action="{{ route('admin.users.impersonate', $user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-emerald-600 hover:text-emerald-900">Login as</button>
                            </form>

                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4 px-4 pb-4">
        {{ $users->links() }}
    </div>
@endsection