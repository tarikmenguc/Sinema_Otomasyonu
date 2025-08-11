{{-- resources/views/admin/films/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', $film->title . ' | Film Detayı')

@section('content')
@php
    // ---------- Poster URL normalizasyonu ----------
    $rawPoster = $film->poster;

    // 1) Eğer http ise https'e zorla
    if ($rawPoster && str_starts_with($rawPoster, 'http://')) {
        $rawPoster = preg_replace('#^http://#', 'https://', $rawPoster);
    }

    // 2) Yerel (storage) yol olabilir → tam URL'ye çevir
    if ($rawPoster && !str_starts_with($rawPoster, 'http')) {
        $rawPoster = \Illuminate\Support\Facades\Storage::url($rawPoster);
    }

    // 3) IMDb/Amazon kalıbı: ..._V1_SX300.jpg → küçük/ büyük varyant
    $posterSmall = $rawPoster ? preg_replace('/_V1_.+\.jpg$/i', '_V1_SX300.jpg', $rawPoster) : null; // kart
    $posterLarge = $rawPoster ? preg_replace('/_V1_.+\.jpg$/i', '_V1_.jpg', $rawPoster) : null;      // hero bg

    // Eğer regex eşleşmediyse fall back
    $posterSmall = $posterSmall ?: $rawPoster;
    $posterLarge = $posterLarge ?: $rawPoster;

    // 4) Fallback görsel
    $fallback = asset('images/placeholder-poster.jpg');
@endphp

<div class="max-w-5xl mx-auto">

  {{-- HERO: geniş arka plan + başlık/rozetler + aksiyonlar --}}
  <div class="relative overflow-hidden rounded-2xl shadow">
    <div class="absolute inset-0">
      <img
        src="{{ $posterLarge ?: $fallback }}"
        alt="Poster: {{ $film->title }}"
        referrerpolicy="no-referrer"
        loading="lazy"
        decoding="async"
        class="h-64 w-full object-cover blur-[2px] scale-105"
        onerror="this.onerror=null;this.src='{{ $fallback }}';"
      >
      <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>

    <div class="relative z-10 p-6 md:p-8 flex items-end h-64">
      <div class="flex items-end gap-6">
        {{-- Küçük poster kartı (masaüstünde) --}}
        <div class="hidden md:block">
          <img
            src="{{ $posterSmall ?: $fallback }}"
            alt="Poster: {{ $film->title }}"
            referrerpolicy="no-referrer"
            loading="lazy"
            decoding="async"
            class="h-48 w-32 object-cover rounded-xl ring-2 ring-white/10 shadow-lg"
            onerror="this.onerror=null;this.src='{{ $fallback }}';"
          >
        </div>

        <div class="text-white">
          <h1 class="text-2xl md:text-3xl font-bold tracking-tight">{{ $film->title }}</h1>

          <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-white/80">
            @if($film->year)
              <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1">{{ $film->year }}</span>
            @endif
            @if($film->runtime)
              <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1">{{ $film->runtime }}</span>
            @endif
            @if($film->genre)
              @foreach(explode(',', $film->genre) as $g)
                <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1">{{ trim($g) }}</span>
              @endforeach
            @endif
            @if($film->imdb_rating)
              <span class="inline-flex items-center rounded-full bg-yellow-400/90 text-black px-3 py-1 font-semibold">
                ★ IMDb {{ $film->imdb_rating }}
              </span>
            @endif
          </div>
        </div>
      </div>

      {{-- Aksiyonlar --}}
      <div class="ml-auto flex items-center gap-2">
        <a href="{{ route('admin.films.index') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2 text-white hover:bg-white/20 transition">
          ← Geri
        </a>

        <form action="{{ route('admin.films.destroy', $film) }}" method="POST"
              onsubmit="return confirm('Bu filmi silmek istediğinize emin misiniz?');">
          @csrf
          @method('DELETE')
          <button type="submit"
                  class="inline-flex items-center gap-2 rounded-xl bg-red-500 px-4 py-2 text-white hover:bg-red-600 transition">
            Sil
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- İçerik grid’i --}}
  <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- Mobilde poster --}}
    <div class="md:hidden">
      <img
        src="{{ $posterSmall ?: $fallback }}"
        alt="Poster: {{ $film->title }}"
        referrerpolicy="no-referrer"
        loading="lazy"
        decoding="async"
        class="w-full aspect-[2/3] object-cover rounded-xl shadow"
        onerror="this.onerror=null;this.src='{{ $fallback }}';"
      >
    </div>

    {{-- Detaylar kartı --}}
    <div class="md:col-span-2 bg-white rounded-2xl shadow p-6">
      <h2 class="text-lg font-semibold text-gray-900 mb-4">Film Bilgileri</h2>

      <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
        @if($film->released)
          <div>
            <dt class="text-sm text-gray-500">Çıkış Tarihi</dt>
            <dd class="text-gray-900 font-medium">{{ $film->released }}</dd>
          </div>
        @endif

        @if($film->director)
          <div>
            <dt class="text-sm text-gray-500">Yönetmen</dt>
            <dd class="text-gray-900 font-medium">{{ $film->director }}</dd>
          </div>
        @endif

        @if($film->actors)
          <div class="sm:col-span-2">
            <dt class="text-sm text-gray-500">Oyuncular</dt>
            <dd class="text-gray-900 font-medium leading-relaxed">
              {{ $film->actors }}
            </dd>
          </div>
        @endif
      </dl>

      @if($film->plot)
        <div class="mt-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Özet</h3>
          <p class="text-gray-700 leading-relaxed">
            {{ $film->plot }}
          </p>
        </div>
      @endif
    </div>

    {{-- Yan panel --}}
    <aside class="bg-white rounded-2xl shadow p-6">
      <h2 class="text-lg font-semibold text-gray-900 mb-4">Öne Çıkanlar</h2>
      <ul class="space-y-3 text-sm text-gray-700">
        <li class="flex items-center justify-between">
          <span class="text-gray-500">IMDb</span>
          <span class="font-semibold">{{ $film->imdb_rating ?: '—' }}</span>
        </li>
        <li class="flex items-center justify-between">
          <span class="text-gray-500">Süre</span>
          <span class="font-semibold">{{ $film->runtime ?: '—' }}</span>
        </li>
        <li class="flex items-center justify-between">
          <span class="text-gray-500">Tür</span>
          <span class="font-semibold">{{ $film->genre ?: '—' }}</span>
        </li>
        <li class="flex items-center justify-between">
          <span class="text-gray-500">Yıl</span>
          <span class="font-semibold">{{ $film->year ?: '—' }}</span>
        </li>
      </ul>

      <div class="mt-6 flex flex-col gap-2">
        <a href="{{ route('admin.films.index') }}"
           class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-4 py-2 text-gray-700 hover:bg-gray-50 transition">
          Tüm Filmler
        </a>
        {{-- İstersen buraya "Seans Oluştur" kısayolu ekle --}}
      </div>
    </aside>

  </div>
</div>
@endsection
