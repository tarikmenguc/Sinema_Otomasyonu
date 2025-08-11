{{-- resources/views/admin/bilet_fiyatlari/index.blade.php --}}
@extends('admin.layouts.app')

@section('title','Bilet Fiyatları')

@section('content')
@php
  $grupSayisi = $fiyatlar->count();
  $para = fn($v) => number_format((float)$v, 2, ',', '.');
@endphp

<div class="max-w-5xl mx-auto space-y-6">

  {{-- Başlık / Özet --}}
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-700 to-violet-600 text-white shadow">
    <div class="p-6 md:p-8 flex items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight">Bilet Fiyatları</h1>
        <p class="text-white/90 text-sm mt-1">Üye tiplerine göre bilet ücretlerini yönetin.</p>
        <div class="mt-4 inline-flex items-center rounded-xl bg-white/15 px-4 py-2">
          <span class="opacity-90 text-sm">Fiyat Grubu</span>
          <span class="text-2xl font-bold ml-3">{{ $grupSayisi }}</span>
        </div>
      </div>
      <a href="{{ route('admin.dashboard') }}"
         class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2 hover:bg-white/25 transition">
        ← Panele Dön
      </a>
    </div>
  </div>

  {{-- Flash --}}
  @if(session('status'))
    <div class="rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3">
      {{ session('status') }}
    </div>
  @endif

  {{-- Masaüstü Tablo --}}
  <div class="hidden md:block overflow-hidden bg-white shadow rounded-2xl border border-gray-200">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-gray-50">
          <tr class="text-left text-xs font-medium text-gray-500 uppercase">
            <th class="px-5 py-3">Üye Tipi</th>
            <th class="px-5 py-3">Fiyat (₺)</th>
            <th class="px-5 py-3 text-right">İşlem</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($fiyatlar as $fiyat)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-5 py-3">
                @php
                  $badge = match($fiyat->uye_tipi) {
                    'ogrenci' => 'bg-blue-100 text-blue-800',
                    'tam'     => 'bg-purple-100 text-purple-800',
                    'vip'     => 'bg-amber-100 text-amber-800',
                    default   => 'bg-gray-100 text-gray-800',
                  };
                @endphp
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badge }}">
                  {{ ucfirst($fiyat->uye_tipi) }}
                </span>
              </td>
              <td class="px-5 py-3 font-semibold text-gray-900">₺ {{ $para($fiyat->fiyat) }}</td>
              <td class="px-5 py-3">
                <div class="flex items-center justify-end">
                  <a href="{{ route('admin.bilet_fiyatlari.edit', $fiyat->id) }}"
                     class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs bg-indigo-600 text-white hover:bg-indigo-700 transition">
                    Düzenle
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="px-5 py-10">
                <div class="text-center text-gray-500">
                  <p class="font-medium">Tanımlı bilet fiyatı bulunamadı.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Mobil Kartlar --}}
  <div class="md:hidden grid grid-cols-1 gap-4">
    @forelse($fiyatlar as $fiyat)
      @php
        $badge = match($fiyat->uye_tipi) {
          'ogrenci' => 'bg-blue-100 text-blue-800',
          'tam'     => 'bg-purple-100 text-purple-800',
          'vip'     => 'bg-amber-100 text-amber-800',
          default   => 'bg-gray-100 text-gray-800',
        };
      @endphp
      <div class="bg-white rounded-2xl shadow p-4">
        <div class="flex items-start justify-between">
          <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badge }}">
            {{ ucfirst($fiyat->uye_tipi) }}
          </span>
          <div class="text-right">
            <div class="text-xs text-gray-500">Fiyat</div>
            <div class="text-base font-semibold text-gray-900">₺ {{ $para($fiyat->fiyat) }}</div>
          </div>
        </div>
        <div class="mt-3">
          <a href="{{ route('admin.bilet_fiyatlari.edit', $fiyat->id) }}"
             class="w-full text-center inline-flex items-center justify-center rounded-xl bg-indigo-600 text-white text-xs px-3 py-2 hover:bg-indigo-700 transition">
            Düzenle
          </a>
        </div>
      </div>
    @empty
      <div class="bg-white rounded-2xl shadow p-6 text-center text-gray-500">
        Tanımlı bilet fiyatı bulunamadı.
      </div>
    @endforelse
  </div>

</div>
@endsection
