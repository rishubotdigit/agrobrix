@extends('layouts.auth')

@section('title', 'Reset Password - Agrobrix')

@section('content')
<div class="bg-white py-8 px-6 shadow-xl rounded-lg sm:px-10">
    <div class="mb-8">
        <h2 class="text-center text-3xl font-extrabold text-gray-900">Reset Password</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Enter your new password
        </p>
        @if($errors->any())
            <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <form action="{{ route('password.reset') }}" method="POST">
        @csrf
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
            <input id="password" name="password" type="password" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary">
        </div>
        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary">
        </div>
        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                Reset Password
            </button>
        </div>
    </form>
</div>
@endsection