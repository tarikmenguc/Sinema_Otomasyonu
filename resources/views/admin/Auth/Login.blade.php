{{-- resources/views/admin/auth/login.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Admin Girişi')

@section('content')
<div class="min-h-[80vh] grid place-items-center px-4">
  <div class="w-full max-w-md">
    {{-- KART --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow border border-gray-100">
      {{-- ÜST ŞERİT / LOGO --}}
      <div class="bg-gradient-to-r from-indigo-700 to-violet-600 text-white p-6">
        <div class="flex items-center gap-3">
          {{-- logo yer tutucu (varsa kendi logonu koy) --}}
          <div class="h-10 w-10 rounded-xl bg-white/20 grid place-items-center text-xl font-bold">A</div>
          <div>
            <h1 class="text-xl font-semibold leading-tight">Yönetim Paneli</h1>
            <p class="text-white/80 text-sm">Devam etmek için giriş yapın</p>
          </div>
        </div>
      </div>

      {{-- FLASH / HATALAR --}}
      @if(session('status'))
        <div class="px-6 pt-4">
          <div class="rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3 text-sm">
            {{ session('status') }}
          </div>
        </div>
      @endif
      @if(session('error'))
        <div class="px-6 pt-4">
          <div class="rounded-xl border border-red-200 bg-red-50 text-red-700 px-4 py-3 text-sm">
            {{ session('error') }}
          </div>
        </div>
      @endif

      {{-- FORM --}}
      <form method="POST" action="{{ route('login.post') }}" class="p-6 space-y-5">
        @csrf

        {{-- E-posta --}}
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-posta</label>
          <div class="relative">
            <input
              id="email"
              type="email"
              name="email"
              value="{{ old('email') }}"
              required
              autofocus
              class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 pl-10 @error('email') border-red-500 @enderror"
              placeholder="ornek@domain.com"
            >
            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2.94 6.34l6.32 3.79c.45.27 1.03.27 1.47 0l6.33-3.79A2 2 0 0015.99 5H4.01a2 2 0 00-1.07 1.34z"/>
              <path d="M18 8.12l-6.76 4.06a3 3 0 01-3.08 0L1.4 8.12A2 2 0 002 9.94V14a2 2 0 002 2h12a2 2 0 002-2V9.94a2 2 0 00.01-1.82z"/>
            </svg>
          </div>
          @error('email')
            <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
          @enderror
        </div>

        {{-- Şifre --}}
        <div>
          <div class="flex items-center justify-between mb-1">
            <label for="password" class="block text-sm font-medium text-gray-700">Şifre</label>
            @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}" class="text-xs text-indigo-600 hover:text-indigo-700">Şifremi unuttum</a>
            @endif
          </div>
          <div class="relative">
            <input
              id="password"
              type="password"
              name="password"
              required
              class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 pr-10 pl-10 @error('password') border-red-500 @enderror"
              placeholder="••••••••"
            >
            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 3C5.5 3 1.73 6.11.46 10c1.27 3.89 5.04 7 9.54 7s8.27-3.11 9.54-7C18.27 6.11 14.5 3 10 3zm0 12a5 5 0 110-10 5 5 0 010 10z"/>
            </svg>
            <button type="button" id="togglePass" class="absolute right-2 top-1.5 rounded-lg px-2 py-1 text-xs text-gray-600 hover:bg-gray-100">
              Göster
            </button>
          </div>
          @error('password')
            <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
          @enderror
        </div>

        {{-- Remember me --}}
        <div class="flex items-center justify-between">
          <label class="inline-flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            Beni Hatırla
          </label>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 text-white py-2.5 font-semibold hover:bg-indigo-700 transition shadow">
          Giriş Yap
        </button>

        {{-- küçük not --}}
        <p class="text-xs text-gray-500 text-center">
          Bu sayfa yalnızca yetkili kullanıcılar içindir.
        </p>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  (function(){
    const btn = document.getElementById('togglePass');
    const input = document.getElementById('password');
    if (!btn || !input) return;
    btn.addEventListener('click', () => {
      const isPwd = input.type === 'password';
      input.type = isPwd ? 'text' : 'password';
      btn.textContent = isPwd ? 'Gizle' : 'Göster';
    });
  })();
</script>
@endpush
