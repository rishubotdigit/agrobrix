@extends('layouts.auth')

@section('title', 'Forgot Password - Agrobrix')

@section('content')
<div class="bg-white py-8 px-6 shadow-xl rounded-lg sm:px-10">
    <div class="mb-8">
        <h2 class="text-center text-3xl font-extrabold text-gray-900">Forgot Password</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            @if(session('forgot_password_mobile'))
                Enter the OTP sent to your mobile number
            @else
                Enter your mobile number to reset your password
            @endif
        </p>
        @if(session('success'))
            <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif
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

    @if(session('forgot_password_mobile'))
    <form action="{{ route('password.verify.otp') }}" method="POST">
        @csrf
        <div>
            <label for="otp" class="block text-sm font-medium text-gray-700">OTP</label>
            <input id="otp" name="otp" type="text" maxlength="6" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-center text-2xl tracking-widest focus:outline-none focus:ring-primary focus:border-primary">
        </div>
        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                Verify OTP
            </button>
        </div>
    </form>
    @else
    <form action="{{ route('password.send.otp') }}" method="POST">
        @csrf
        <div>
            <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile Number</label>
            <input id="mobile" name="mobile" type="text" placeholder="+1234567890" required
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary">
        </div>
        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                Send OTP
            </button>
        </div>
    </form>
    @endif

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            Remember your password?
            <a href="{{ route('login') }}" class="font-medium text-primary hover:text-primary-dark transition">
                Sign in
            </a>
        </p>
    </div>
</div>
@endsection