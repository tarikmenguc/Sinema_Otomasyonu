{{-- resources/views/admin/bilets/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', "Seans #{$seans->id} Biletleri")

@section('content')
@php
    $toplam   = $biletler->count();
    $aktif    = $biletler->where('is_active', true)->count();
    $pasif    = $biletler->where('is_active', false)->count();
    $odenen   = $biletler->where('status', 'odendi')->count();
    $bekleyen = $biletler->where('status', 'bekliyor')->count();
    $basarisiz= $biletler->where('status', 'basarisiz')->count();

    $gelir    = $biletler->where('status','odendi')->sum('fiyat');
@endphp

<div class="max-w-6xl mx-auto space-y-6">

  {{-- HERO / Başlık ve seans özeti --}}
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-700 to-violet-600 text-white shadow">
    <div class="p-6 md:p-8">
      <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
          <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-sm">
            <span class="opacity-90">Seans</span>
            <span class="font-semibold">#{{ $seans->id }}</span>
          </div>
          <h1 class="mt-3 text-2xl md:text-3xl font-bold tracking-tight">
            {{ $seans->film->title ?? 'Film' }}
          </h1>
          <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-white/90">
            <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1">
              Salon: <strong class="ml-1">{{ optional($seans->salon)->Salon_adi ?? '—' }}</strong>
            </span>
            <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1">
              Başlangıç: <strong class="ml-1">{{ $seans->baslama_zamani->format('d.m.Y H:i') }}</strong>
            </span>
            <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1">
              Bitiş: <strong class="ml-1">{{ $seans->bitis_zamani->format('d.m.Y H:i') }}</strong>
            </span>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <a href="{{ route('admin.bilets.index') }}"
             class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2 hover:bg-white/25 transition">
            ← Geri
          </a>
          <a href="{{ route('admin.seans.show', $seans) }}"
             class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2 hover:bg-white/25 transition">
            Seans Detayı
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- Özet metrikler --}}
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
    <div class="rounded-2xl bg-white shadow p-4">
      <div class="text-sm text-gray-500">Toplam Bilet</div>
      <div class="text-2xl font-bold text-gray-900">{{ $toplam }}</div>
    </div>
    <div class="rounded-2xl bg-white shadow p-4">
      <div class="text-sm text-gray-500">Aktif</div>
      <div class="text-2xl font-bold text-emerald-600">{{ $aktif }}</div>
    </div>
    <div class="rounded-2xl bg-white shadow p-4">
      <div class="text-sm text-gray-500">Pasif</div>
      <div class="text-2xl font-bold text-gray-700">{{ $pasif }}</div>
    </div>
    <div class="rounded-2xl bg-white shadow p-4">
      <div class="text-sm text-gray-500">Ödenen</div>
      <div class="text-2xl font-bold text-indigo-600">{{ $odenen }}</div>
      <div class="text-xs text-gray-500 mt-1">Gelir: <strong class="text-gray-800">{{ number_format($gelir, 2, ',', '.') }}</strong></div>
    </div>
    <div class="rounded-2xl bg-white shadow p-4">
      <div class="text-sm text-gray-500">Bekleyen</div>
      <div class="text-2xl font-bold text-amber-600">{{ $bekleyen }}</div>
    </div>
    <div class="rounded-2xl bg-white shadow p-4">
      <div class="text-sm text-gray-500">Başarısız</div>
      <div class="text-2xl font-bold text-red-600">{{ $basarisiz }}</div>
    </div>
  </div>

  @if($biletler->isEmpty())
    <div class="rounded-2xl border border-gray-200 bg-gray-50 text-gray-600 p-6 text-center">
      Bu seans için henüz bilet kesilmemiş.
    </div>
  @else

    {{-- Masaüstü: Tablo --}}
    <div class="hidden md:block overflow-hidden bg-white shadow rounded-2xl border border-gray-200">
      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead class="bg-gray-50">
            <tr class="text-left text-xs font-medium text-gray-500 uppercase">
              <th class="px-5 py-3">#</th>
              <th class="px-5 py-3">Kullanıcı</th>
              <th class="px-5 py-3">Koltuk</th>
              <th class="px-5 py-3">Tarih</th>
              <th class="px-5 py-3">Durum</th>
              <th class="px-5 py-3">Ödeme</th>
              <th class="px-5 py-3 text-right">İşlemler</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @foreach($biletler as $bilet)
              @php
                $isActive = (bool)$bilet->is_active;
                $status   = $bilet->status; // odendi/bekliyor/basarisiz
                $statusBadge = match($status) {
                  'odendi'    => 'bg-emerald-100 text-emerald-800',
                  'bekliyor'  => 'bg-amber-100 text-amber-800',
                  'basarisiz' => 'bg-red-100 text-red-800',
                  default     => 'bg-gray-100 text-gray-800',
                };
              @endphp
              <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3 font-medium text-gray-700">{{ $bilet->id }}</td>
                <td class="px-5 py-3">
                  <div class="font-semibold text-gray-900">{{ $bilet->user->name }}</div>
                  <div class="text-xs text-gray-500">{{ $bilet->user->email }}</div>
                </td>
                <td class="px-5 py-3">
                  <span class="inline-flex items-center rounded-xl bg-gray-100 px-2.5 py-0.5 text-sm text-gray-800">
                    {{ $bilet->koltuk->koltuk_no }}
                  </span>
                </td>
                <td class="px-5 py-3 text-gray-700">{{ $bilet->created_at->format('d.m.Y H:i') }}</td>
                <td class="px-5 py-3">
                  @if($isActive)
                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Aktif</span>
                  @else
                    <span class="inline-flex items-center rounded-full bg-gray-200 px-2.5 py-0.5 text-xs font-semibold text-gray-800">Pasif</span>
                  @endif
                </td>
                <td class="px-5 py-3">
                  <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusBadge }}">
                    {{ strtoupper($status) }}
                  </span>
                  @if(!is_null($bilet->fiyat))
                    <div class="text-xs text-gray-500 mt-1">₺ {{ number_format($bilet->fiyat, 2, ',', '.') }}</div>
                  @endif
                </td>
                <td class="px-5 py-3">
                  <div class="flex items-center justify-end">
                    <form action="{{ route('admin.bilets.toggle', $bilet) }}" method="POST"
                          onsubmit="return confirm('Durumunu değiştirmek istediğinize emin misiniz?')">
                      @csrf
                      @method('PATCH')
                      <button
                        class="inline-flex items-center px-3 py-1.5 rounded-xl bg-amber-500 text-white text-xs hover:bg-amber-600 transition">
                        {{ $isActive ? 'Pasif Yap' : 'Aktif Yap' }}
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- Mobil: Kart listesi --}}
    <div class="md:hidden grid grid-cols-1 gap-4">
      @foreach($biletler as $bilet)
        @php
          $isActive = (bool)$bilet->is_active;
          $status   = $bilet->status;
          $statusBadge = match($status) {
            'odendi'    => 'bg-emerald-100 text-emerald-800',
            'bekliyor'  => 'bg-amber-100 text-amber-800',
            'basarisiz' => 'bg-red-100 text-red-800',
            default     => 'bg-gray-100 text-gray-800',
          };
        @endphp
        <div class="bg-white rounded-2xl shadow p-4">
          <div class="flex items-start justify-between gap-2">
            <div>
              <div class="font-semibold text-gray-900">#{{ $bilet->id }} — Koltuk {{ $bilet->koltuk->koltuk_no }}</div>
              <div class="text-sm text-gray-700">{{ $bilet->user->name }}</div>
              <div class="text-xs text-gray-500">{{ $bilet->user->email }}</div>
            </div>
            <div class="text-right">
              <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $statusBadge }}">
                {{ strtoupper($status) }}
              </span>
              @if(!is_null($bilet->fiyat))
                <div class="text-xs text-gray-500 mt-1">₺ {{ number_format($bilet->fiyat, 2, ',', '.') }}</div>
              @endif
            </div>
          </div>

          <div class="mt-2 text-sm text-gray-600">
            {{ $bilet->created_at->format('d.m.Y H:i') }}
            <span class="mx-2">•</span>
            @if($isActive)
              <span class="text-emerald-700 font-medium">Aktif</span>
            @else
              <span class="text-gray-700 font-medium">Pasif</span>
            @endif
          </div>

          <div class="mt-3 flex items-center gap-2">
            <form action="{{ route('admin.bilets.toggle', $bilet) }}" method="POST"
                  class="flex-1"
                  onsubmit="return confirm('Durumunu değiştirmek istediğinize emin misiniz?')">
              @csrf
              @method('PATCH')
              <button class="w-full text-center rounded-xl bg-amber-500 text-white text-xs px-3 py-2 hover:bg-amber-600 transition">
                {{ $isActive ? 'Pasif Yap' : 'Aktif Yap' }}
              </button>
            </form>
          </div>
        </div>
      @endforeach
    </div>

  @endif

</div>
@endsection
