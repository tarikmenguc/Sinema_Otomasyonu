{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layouts.app')

@section('title','Yönetici Paneli')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-8">

  {{-- HERO --}}
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-700 to-violet-600 text-white shadow">
    <div class="p-6 md:p-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight">Yönetici Paneli</h1>
        <p class="text-white/90 text-sm mt-1">Sistem genel bakış, hızlı erişim ve son aktiviteler.</p>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('admin.seans.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2 hover:bg-white/25 transition">
          + Yeni Seans
        </a>
      </div>
    </div>
  </div>

  {{-- ÖZET METRİK KARTLARI --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    {{-- Toplam Film --}}
    <div class="bg-white rounded-2xl shadow p-5 border border-gray-100">
      <div class="flex items-center justify-between">
        <h2 class="text-sm font-medium text-gray-500">Toplam Film</h2>
        <span class="h-8 w-8 grid place-items-center rounded-xl bg-indigo-50 text-indigo-600">🎬</span>
      </div>
      <div class="mt-2 text-4xl font-bold text-gray-900">{{ \App\Models\Film::count() }}</div>
      <a href="{{ route('admin.films.index') }}" class="mt-3 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-700">
        Tüm Filmler →
      </a>
    </div>

    {{-- Toplam Salon --}}
    <div class="bg-white rounded-2xl shadow p-5 border border-gray-100">
      <div class="flex items-center justify-between">
        <h2 class="text-sm font-medium text-gray-500">Toplam Salon</h2>
        <span class="h-8 w-8 grid place-items-center rounded-xl bg-emerald-50 text-emerald-600">🏟️</span>
      </div>
      <div class="mt-2 text-4xl font-bold text-gray-900">{{ \App\Models\Salon::count() }}</div>
      <a href="{{ route('admin.salon.index') }}" class="mt-3 inline-flex items-center text-sm text-emerald-600 hover:text-emerald-700">
        Tüm Salonlar →
      </a>
    </div>

    {{-- Aktif Seanslar --}}
    @php
      $aktifSeans = \App\Models\Seans::where('is_active', true)->where('bitis_zamani','>', now())->count();
    @endphp
    <div class="bg-white rounded-2xl shadow p-5 border border-gray-100">
      <div class="flex items-center justify-between">
        <h2 class="text-sm font-medium text-gray-500">Aktif Seanslar</h2>
        <span class="h-8 w-8 grid place-items-center rounded-xl bg-amber-50 text-amber-600">⏱️</span>
      </div>
      <div class="mt-2 text-4xl font-bold text-gray-900">{{ $aktifSeans }}</div>
      <a href="{{ route('admin.seans.index') }}" class="mt-3 inline-flex items-center text-sm text-amber-600 hover:text-amber-700">
        Seansları Gör →
      </a>
    </div>

    {{-- Toplam Bilet --}}
    <div class="bg-white rounded-2xl shadow p-5 border border-gray-100">
      <div class="flex items-center justify-between">
        <h2 class="text-sm font-medium text-gray-500">Toplam Bilet</h2>
        <span class="h-8 w-8 grid place-items-center rounded-xl bg-rose-50 text-rose-600">🎟️</span>
      </div>
      <div class="mt-2 text-4xl font-bold text-gray-900">{{ \App\Models\Bilet::count() }}</div>
      <a href="{{ route('admin.bilets.index') }}" class="mt-3 inline-flex items-center text-sm text-rose-600 hover:text-rose-700">
        Bilet Yönetimi →
      </a>
    </div>
  </div>

  {{-- HIZLI ERİŞİM --}}
  <div>
    <h2 class="text-xl font-semibold text-gray-900 mb-3">Hızlı Erişim</h2>
    <div class="flex flex-wrap gap-3">
      <a href="{{ route('admin.films.index') }}"
         class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 text-white px-4 py-2 hover:bg-indigo-700 transition">
        Filmler
      </a>
      <a href="{{ route('admin.salon.index') }}"
         class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 text-white px-4 py-2 hover:bg-emerald-700 transition">
        Salonlar
      </a>
      <a href="{{ route('admin.seans.index') }}"
         class="inline-flex items-center gap-2 rounded-xl bg-amber-600 text-white px-4 py-2 hover:bg-amber-700 transition">
        Seanslar
      </a>
      <a href="{{ route('admin.bilets.index') }}"
         class="inline-flex items-center gap-2 rounded-xl bg-rose-600 text-white px-4 py-2 hover:bg-rose-700 transition">
        Biletler
      </a>
    </div>
  </div>

  {{-- SON AKTİVİTELER --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Son Eklenen Filmler --}}
    <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-semibold text-gray-900">Son Eklenen Filmler</h3>
        <a href="{{ route('admin.films.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Tümü</a>
      </div>
      <div class="divide-y divide-gray-100">
        @forelse(\App\Models\Film::latest()->take(5)->get() as $film)
          <a href="{{ route('admin.films.show', $film) }}" class="block py-3 hover:bg-gray-50 rounded px-2">
            <div class="font-medium text-gray-900">{{ $film->title }}</div>
            <div class="text-xs text-gray-500">Yıl: {{ $film->year }} · ID: {{ $film->id }}</div>
          </a>
        @empty
          <div class="py-6 text-center text-gray-500">Henüz film eklenmemiş.</div>
        @endforelse
      </div>
    </div>

    {{-- Yaklaşan Seanslar (24 Saat) --}}
    <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-semibold text-gray-900">Yaklaşan Seanslar (24 Saat)</h3>
        <a href="{{ route('admin.seans.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Tümü</a>
      </div>
      <div class="divide-y divide-gray-100">
        @php
          $yaklasan = \App\Models\Seans::where('baslama_zamani','>=', now())
                      ->where('baslama_zamani','<', now()->addDay())
                      ->with(['film','salon'])
                      ->orderBy('baslama_zamani')
                      ->get();
        @endphp
        @forelse($yaklasan as $s)
          <div class="py-3 px-2">
            <div class="font-medium text-gray-900">{{ $s->film->title }}</div>
            <div class="text-sm text-gray-600">
              {{ $s->salon->Salon_adi }} — 
              <span class="text-blue-600 font-medium">{{ $s->baslama_zamani->format('d.m.Y H:i') }}</span>
            </div>
          </div>
        @empty
          <div class="py-6 text-center text-gray-500">Önümüzdeki 24 saatte seans yok.</div>
        @endforelse
      </div>
    </div>

    {{-- Son Satılan Biletler --}}
    <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-semibold text-gray-900">Son Satılan Biletler</h3>
        <a href="{{ route('admin.bilets.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Tümü</a>
      </div>
      <div class="divide-y divide-gray-100">
        @forelse(\App\Models\Bilet::latest()->take(5)->with(['user','seans.film'])->get() as $bilet)
          <div class="py-3 px-2">
            <div class="font-medium text-gray-900">{{ $bilet->user->name }}</div>
            <div class="text-sm text-gray-600">
              {{ $bilet->seans->film->title }} /
              <span class="text-blue-600 font-medium">{{ $bilet->seans->baslama_zamani->format('d.m.Y H:i') }}</span>
            </div>
          </div>
        @empty
          <div class="py-6 text-center text-gray-500">Henüz bilet satışı yok.</div>
        @endforelse
      </div>
    </div>
  </div>

</div>
@endsection
