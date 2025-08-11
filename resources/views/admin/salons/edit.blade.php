{{-- resources/views/admin/salons/show.blade.php --}}
@extends('admin.layouts.app')
@section('title','Salon Detayı')

@section('content')
@php
  $mevcutKoltukSayisi = $salon->koltuks->count();
  $maxEklenebilir     = max(0, $salon->kapasite - $mevcutKoltukSayisi);
@endphp

<div class="max-w-6xl mx-auto space-y-6">

  {{-- FLASH MESAJI --}}
  @if(session('status'))
    <div class="rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3">
      {{ session('status') }}
    </div>
  @endif
  @if($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 text-red-700 px-4 py-3">
      <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  {{-- HERO --}}
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-700 to-violet-600 text-white shadow">
    <div class="p-6 md:p-8">
      <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
          <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-sm">
            <span class="opacity-90">Salon</span>
            <span class="font-semibold">{{ $salon->id }}</span>
          </div>
          <h1 class="mt-3 text-2xl md:text-3xl font-bold tracking-tight">{{ $salon->Salon_adi }}</h1>
          <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-white/90">
            <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1">
              Kapasite: <strong class="ml-1">{{ $salon->kapasite }}</strong>
            </span>
            <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1">
              Mevcut Koltuk: <strong class="ml-1">{{ $mevcutKoltukSayisi }}</strong>
            </span>
            @if($salon->aktifmi)
              <span class="inline-flex items-center rounded-full bg-emerald-400/90 text-black px-3 py-1 font-semibold">Aktif</span>
            @else
              <span class="inline-flex items-center rounded-full bg-gray-300 text-black px-3 py-1 font-semibold">Pasif</span>
            @endif
          </div>
        </div>

        <div class="flex items-center gap-2">
          <a href="{{ route('admin.salon.index') }}"
             class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2 hover:bg-white/25 transition">
            ← Geri
          </a>
          <form action="{{ route('admin.salon.toggle', $salon) }}" method="POST"
                onsubmit="return confirm('Durumu değiştirmek istediğinize emin misiniz?');">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-amber-400 text-black px-4 py-2 font-semibold hover:bg-amber-300 transition">
              {{ $salon->aktifmi ? 'Pasifleştir' : 'Aktifleştir' }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- ÜST KARTLAR: BİLGİ + KAPASİTE + KOLTUK OLUŞTUR --}}
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- Salon Bilgileri --}}
    <div class="bg-white rounded-2xl shadow p-6 md:col-span-2">
      <h2 class="text-lg font-semibold text-gray-900 mb-4">Salon Bilgileri</h2>
      <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
        <div>
          <dt class="text-sm text-gray-500">ID</dt>
          <dd class="text-gray-900 font-medium">{{ $salon->id }}</dd>
        </div>
        <div>
          <dt class="text-sm text-gray-500">Salon Adı</dt>
          <dd class="text-gray-900 font-medium">{{ $salon->Salon_adi }}</dd>
        </div>
        <div>
          <dt class="text-sm text-gray-500">Kapasite</dt>
          <dd class="text-gray-900 font-medium">{{ $salon->kapasite }}</dd>
        </div>
        <div>
          <dt class="text-sm text-gray-500">Durum</dt>
          <dd class="text-gray-900 font-medium">
            @if($salon->aktifmi)
              <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-800">Aktif</span>
            @else
              <span class="inline-flex items-center rounded-full bg-gray-200 px-2.5 py-1 text-xs font-semibold text-gray-800">Pasif</span>
            @endif
          </dd>
        </div>
      </dl>
    </div>

    {{-- Kapasite Güncelle --}}
    <div class="bg-white rounded-2xl shadow p-6">
      <h2 class="text-lg font-semibold text-gray-900 mb-3">Kapasiteyi Güncelle</h2>
      <form action="{{ route('admin.salons.updateCapacity', $salon->id) }}" method="POST" class="space-y-3">
        @csrf
        @method('PUT')
        <label class="block text-sm text-gray-600 mb-1">Yeni Kapasite</label>
        <input type="number" name="kapasite" min="1" max="500" required
               value="{{ old('kapasite', $salon->kapasite) }}"
               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2">
        <button type="submit"
                class="w-full rounded-xl bg-indigo-600 text-white px-4 py-2 font-semibold hover:bg-indigo-700 transition">
          Güncelle
        </button>
      </form>
    </div>
  </div>

  {{-- Koltuk Oluşturma --}}
  <div class="bg-white rounded-2xl shadow p-6">
    <div class="flex items-start justify-between gap-4 flex-col md:flex-row">
      <div>
        <h2 class="text-lg font-semibold text-gray-900">Koltuk Oluştur</h2>
        <p class="text-sm text-gray-500 mt-1">
          Mevcut: <strong>{{ $mevcutKoltukSayisi }}</strong> — Eklenebilir: <strong>{{ $maxEklenebilir }}</strong>
        </p>
      </div>

      <form action="{{ route('admin.salons.generateSeats', $salon->id) }}" method="POST" class="flex items-end gap-3">
        @csrf
        <div>
          <label class="block text-sm text-gray-600 mb-1">Adet</label>
          <input type="number" name="adet" min="1" max="{{ $maxEklenebilir }}" required
                 placeholder="Max: {{ $maxEklenebilir }}"
                 class="rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 w-40"
                 {{ $maxEklenebilir <= 0 ? 'disabled' : '' }}>
        </div>
        <button type="submit"
                class="rounded-xl bg-blue-600 text-white px-4 py-2 font-semibold hover:bg-blue-700 transition disabled:opacity-50"
                {{ $maxEklenebilir <= 0 ? 'disabled' : '' }}>
          Oluştur
        </button>
      </form>
    </div>

    @if ($maxEklenebilir <= 0)
      <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 text-amber-800 px-4 py-2">
        Bu salonun kapasitesi dolmuş, yeni koltuk ekleyemezsiniz.
      </div>
    @endif
  </div>

  {{-- Aktif Seanslar --}}
  <div class="bg-white rounded-2xl shadow p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Aktif Seanslar</h2>
    @if($salon->seanslar->isEmpty())
      <p class="text-gray-600">Bu salonda şu anda aktif bir seans yok.</p>
    @else
      <ul class="divide-y divide-gray-100">
        @foreach($salon->seanslar as $s)
          <li class="py-3 flex items-center justify-between hover:bg-gray-50 rounded-lg px-2">
            <div>
              <p class="font-medium text-gray-900">{{ $s->film->title }}</p>
              <p class="text-sm text-gray-500">
                {{ $s->baslama_zamani->format('d.m.Y H:i') }} &rarr; {{ $s->bitis_zamani->format('d.m.Y H:i') }}
              </p>
            </div>
            <a href="{{ route('admin.seans.show', $s) }}"
               class="px-3 py-1.5 rounded-xl bg-indigo-600 text-white text-xs hover:bg-indigo-700 transition">
              Görüntüle
            </a>
          </li>
        @endforeach
      </ul>
    @endif
  </div>

  {{-- Koltuk Listesi --}}
  <div class="bg-white rounded-2xl shadow p-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-semibold text-gray-900">Koltuk Listesi</h2>
      <div class="flex items-center gap-2 text-sm">
        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-gray-700">
          Toplam: {{ $salon->koltuks->count() }}
        </span>
        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-emerald-700">
          Aktif: {{ $salon->koltuks->where('is_active', true)->count() }}
        </span>
      </div>
    </div>

    @php
      $aktifKoltuklar = $salon->koltuks->where('is_active', true)->sortBy('koltuk_no');
    @endphp

    @if($aktifKoltuklar->isEmpty())
      <div class="rounded-xl border border-gray-200 bg-gray-50 text-gray-600 p-6 text-center">
        Henüz koltuk oluşturulmamış.
      </div>
    @else
      <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
        @foreach($aktifKoltuklar as $k)
          <div class="relative">
            <div class="p-3 text-center rounded-xl border border-gray-200 bg-white font-semibold text-gray-800 shadow-sm">
              {{ $k->koltuk_no }}
            </div>
            {{-- istersen durum rozetleri ekleyebilirsin --}}
            {{-- <span class="absolute -top-2 -right-2 text-[10px] rounded-full px-2 py-0.5 bg-gray-800 text-white">A</span> --}}
          </div>
        @endforeach
      </div>
    @endif
  </div>

</div>
@endsection
