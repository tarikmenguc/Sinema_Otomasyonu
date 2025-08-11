{{-- resources/views/admin/bilet_fiyatlari/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title','Bilet Fiyatını Güncelle')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

  {{-- FLASH / HATALAR --}}
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
    <div class="p-6 md:p-7 flex items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight">Bilet Fiyatını Güncelle</h1>
        <p class="text-white/90 text-sm mt-1">
          Üye Tipi: <span class="font-semibold">{{ ucfirst($fiyat->uye_tipi) }}</span>
        </p>
      </div>
      <a href="{{ route('admin.bilet_fiyatlari.index') }}"
         class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2 hover:bg-white/25 transition">
        ← Geri
      </a>
    </div>
  </div>

  {{-- FORM --}}
  <form action="{{ route('admin.bilet_fiyatlari.update', $fiyat->id) }}" method="POST"
        class="bg-white rounded-2xl shadow p-6 space-y-5">
    @csrf
    @method('PUT')

    {{-- Üye Tipi (readonly) --}}
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Üye Tipi</label>
      <input type="text"
             class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-700 px-3 py-2"
             value="{{ ucfirst($fiyat->uye_tipi) }}" disabled>
      <p class="text-xs text-gray-500 mt-1">Üye tipi bu ekranda değiştirilemez.</p>
    </div>

    {{-- Fiyat --}}
    <div>
      <label for="fiyat" class="block text-sm font-medium text-gray-700 mb-1">Fiyat (₺)</label>
      <div class="relative">
        <span class="absolute left-3 top-2.5 text-gray-400">₺</span>
        <input
          id="fiyat"
          name="fiyat"
          type="number"
          inputmode="decimal"
          step="0.01"
          min="0"
          value="{{ old('fiyat', $fiyat->fiyat) }}"
          required
          class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 pl-8 px-3 py-2 @error('fiyat') border-red-500 @enderror"
          placeholder="0,00">
      </div>
      @error('fiyat')
        <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
      @enderror
      <p class="text-xs text-gray-500 mt-1">Kurus dahil giriniz. Örn: 120.50</p>
    </div>

    {{-- BUTONLAR --}}
    <div class="flex items-center gap-2 pt-2">
      <button type="submit"
              class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 text-white px-4 py-2 font-semibold hover:bg-emerald-700 transition">
        Güncelle
      </button>
      <a href="{{ route('admin.bilet_fiyatlari.index') }}"
         class="inline-flex items-center gap-2 rounded-xl bg-gray-200 text-gray-800 px-4 py-2 hover:bg-gray-300 transition">
        İptal
      </a>
    </div>
  </form>

  {{-- İPUCU --}}
  <div class="bg-white rounded-2xl shadow p-5">
    <h3 class="text-base font-semibold text-gray-900 mb-2">İpucu</h3>
    <p class="text-sm text-gray-600">
      Fiyat değişikliği yeni kesilecek biletlerde geçerlidir; bekleyen/ödenmiş biletleri etkilemez.
    </p>
  </div>

</div>
@endsection
