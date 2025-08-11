{{-- resources/views/admin/bilets/index.blade.php --}}
@extends('admin.layouts.app')
@section('title', 'Bilet Yönetimi')

@section('content')
@php
    // Özet metrikler
    $toplamSeans   = $seanslar->count();
    $toplamBilet   = $seanslar->sum('bilets_count');
    // Ortalama doluluk için salon kapasitesi varsa hesapla
    $toplamKapasite = $seanslar->sum(fn($s) => optional($s->salon)->kapasite ?? 0);
    $ortalamaDoluluk = $toplamKapasite > 0 ? round(($toplamBilet / $toplamKapasite) * 100) : null;
@endphp

<div class="max-w-6xl mx-auto space-y-6">

  {{-- HERO / Başlık + özet --}}
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-700 to-violet-600 text-white shadow">
    <div class="p-6 md:p-8">
      <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
          <h1 class="text-2xl md:text-3xl font-bold tracking-tight">Bilet Yönetimi</h1>
          <p class="text-white/90 mt-1">Seans bazlı satılan bilet sayıları ve doluluk bilgileri.</p>
          <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
            <div class="rounded-xl bg-white/15 px-4 py-2">
              <div class="opacity-90">Toplam Seans</div>
              <div class="text-2xl font-bold">{{ $toplamSeans }}</div>
            </div>
            <div class="rounded-xl bg-white/15 px-4 py-2">
              <div class="opacity-90">Toplam Satış</div>
              <div class="text-2xl font-bold">{{ $toplamBilet }}</div>
            </div>
            <div class="rounded-xl bg-white/15 px-4 py-2">
              <div class="opacity-90">Ort. Doluluk</div>
              <div class="text-2xl font-bold">
                {{ $ortalamaDoluluk !== null ? ($ortalamaDoluluk . '%') : '—' }}
              </div>
            </div>
          </div>
        </div>
        <a href="{{ route('admin.seans.index') }}"
           class="self-start md:self-auto inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2 hover:bg-white/25 transition">
          ← Seans Listesi
        </a>
      </div>
    </div>
  </div>

  {{-- Bildirim (flash) --}}
  @if(session('status'))
    <div class="rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3">
      {{ session('status') }}
    </div>
  @endif

  {{-- Masaüstü: Tablo --}}
  <div class="hidden md:block overflow-hidden bg-white shadow rounded-2xl border border-gray-200">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-gray-50">
          <tr class="text-left text-xs font-medium text-gray-500 uppercase">
            <th class="px-5 py-3">#</th>
            <th class="px-5 py-3">Film</th>
            <th class="px-5 py-3">Salon</th>
            <th class="px-5 py-3">Başlangıç</th>
            <th class="px-5 py-3">Bitiş</th>
            <th class="px-5 py-3">Doluluk</th>
            <th class="px-5 py-3 text-right">İşlemler</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($seanslar as $seans)
            @php
              $kapasite = optional($seans->salon)->kapasite ?? 0;
              $satis    = $seans->bilets_count;
              $yuzde    = $kapasite > 0 ? round(($satis / $kapasite) * 100) : 0;
              $barColor = $yuzde >= 80 ? 'bg-emerald-500' : ($yuzde >= 50 ? 'bg-amber-500' : 'bg-gray-400');
            @endphp
            <tr class="hover:bg-gray-50 transition">
              <td class="px-5 py-3 text-gray-700 font-medium">{{ $seans->id }}</td>
              <td class="px-5 py-3">
                <div class="font-semibold text-gray-900">{{ $seans->film->title }}</div>
                <div class="text-xs text-gray-500">ID: {{ $seans->film_id }}</div>
              </td>
              <td class="px-5 py-3 text-gray-700">
                {{ optional($seans->salon)->Salon_adi ?? '—' }}
                @if($kapasite)
                  <span class="ml-2 inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-[11px] text-gray-700">
                    Kapasite: {{ $kapasite }}
                  </span>
                @endif
              </td>
              <td class="px-5 py-3 text-blue-600 font-medium">{{ $seans->baslama_zamani->format('d.m.Y H:i') }}</td>
              <td class="px-5 py-3 text-red-500 font-medium">{{ $seans->bitis_zamani->format('d.m.Y H:i') }}</td>
              <td class="px-5 py-3">
                <div class="text-sm text-gray-700 mb-1">
                  Satılan: <span class="font-semibold">{{ $satis }}</span>
                  @if($kapasite) / {{ $kapasite }} ({{ $yuzde }}%) @endif
                </div>
                <div class="h-2.5 w-48 bg-gray-200 rounded-full overflow-hidden">
                  <div class="h-full {{ $barColor }}" style="width: {{ min($yuzde,100) }}%"></div>
                </div>
              </td>
              <td class="px-5 py-3">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.bilets.showbySeans', $seans) }}"
                     class="px-3 py-1.5 rounded-xl bg-indigo-600 text-white text-xs hover:bg-indigo-700 transition">
                    Biletleri Gör
                  </a>
                  <a href="{{ route('admin.seans.show', $seans) }}"
                     class="px-3 py-1.5 rounded-xl border border-gray-200 text-gray-700 text-xs hover:bg-gray-50 transition">
                    Seans Detayı
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-5 py-10">
                <div class="text-center text-gray-500">
                  <p class="font-medium">Gösterilecek seans bulunamadı.</p>
                  <a href="{{ route('admin.seans.create') }}"
                     class="inline-flex mt-3 rounded-xl border border-gray-200 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                    Yeni seans oluştur
                  </a>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Mobil: Kart listesi --}}
  <div class="md:hidden grid grid-cols-1 gap-4">
    @forelse($seanslar as $seans)
      @php
        $kapasite = optional($seans->salon)->kapasite ?? 0;
        $satis    = $seans->bilets_count;
        $yuzde    = $kapasite > 0 ? round(($satis / $kapasite) * 100) : 0;
        $barColor = $yuzde >= 80 ? 'bg-emerald-500' : ($yuzde >= 50 ? 'bg-amber-500' : 'bg-gray-400');
      @endphp
      <div class="bg-white rounded-2xl shadow p-4">
        <div class="flex items-start justify-between">
          <div>
            <h3 class="text-base font-semibold text-gray-900">{{ $seans->film->title }}</h3>
            <p class="text-sm text-gray-500 mt-0.5">
              {{ optional($seans->salon)->Salon_adi ?? '—' }}
              @if($kapasite) · Kap: {{ $kapasite }} @endif
            </p>
            <p class="text-sm text-gray-600 mt-1">
              <span class="text-blue-600 font-medium">{{ $seans->baslama_zamani->format('d.m.Y H:i') }}</span>
              &rarr;
              <span class="text-red-500 font-medium">{{ $seans->bitis_zamani->format('d.m.Y H:i') }}</span>
            </p>
          </div>
          <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-[11px] text-gray-700">
            Satılan: {{ $satis }}
          </span>
        </div>

        <div class="mt-3">
          <div class="h-2.5 w-full bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full {{ $barColor }}" style="width: {{ min($yuzde,100) }}%"></div>
          </div>
          @if($kapasite)
            <div class="mt-1 text-xs text-gray-500 text-right">{{ $yuzde }}%</div>
          @endif
        </div>

        <div class="mt-4 flex items-center gap-2">
          <a href="{{ route('admin.bilets.showbySeans', $seans) }}"
             class="flex-1 text-center rounded-xl bg-indigo-600 text-white text-xs px-3 py-2 hover:bg-indigo-700 transition">
            Biletleri Gör
          </a>
          <a href="{{ route('admin.seans.show', $seans) }}"
             class="flex-1 text-center rounded-xl border border-gray-200 text-gray-700 text-xs px-3 py-2 hover:bg-gray-50 transition">
            Seans Detayı
          </a>
        </div>
      </div>
    @empty
      <div class="bg-white rounded-2xl shadow p-6 text-center text-gray-500">
        Gösterilecek seans bulunamadı.
      </div>
    @endforelse
  </div>

  {{-- (Varsa) sayfalama --}}
  @if(method_exists($seanslar, 'links'))
    <div class="pt-2">{{ $seanslar->appends(request()->query())->links() }}</div>
  @endif

</div>
@endsection
