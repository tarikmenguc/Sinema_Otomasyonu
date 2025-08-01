@extends('admin.layouts.app')

@section('title', 'Admin Girişi')

@section('content')
<div class="max-w-md mx-auto mt-20 bg-white p-6 rounded shadow">
    <h2 class="text-2xl mb-4 text-center">Admin Girişi</h2>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        {{-- E‑posta --}}
        <div class="mb-4">
            <label class="block mb-1">E‑posta</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror"
                required
                autofocus
            >
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Şifre --}}
        <div class="mb-4">
            <label class="block mb-1">Şifre</label>
            <input
                type="password"
                name="password"
                class="w-full border rounded px-3 py-2 @error('password') border-red-500 @enderror"
                required
            >
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="mb-4 flex items-center">
            <input type="checkbox" name="remember" id="remember" class="mr-2">
            <label for="remember">Beni Hatırla</label>
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
        >
            Giriş Yap
        </button>
    </form>
</div>
@endsection
