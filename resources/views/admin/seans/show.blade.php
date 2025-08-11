{{-- resources/views/admin/seans/show.blade.php --}}
@extends('admin.layouts.app')
@section('title', "Seans Detayı #{$seans->id}")

@section('content')
@php
  // Koltuk istatistikleri
  $koltuklar = $seans->salon->koltuks->where('is_active', true)->sortBy('koltuk_no');
  $doluKoltukIds = collect();
  foreach ($koltuklar as $k) {
      $dolu = (bool) $k->biletForSeans($seans->id);
      if ($dolu) $doluKoltukIds->push($k->id);
  }
  $toplam = $koltuklar->count();
  $dolu   = $doluKoltukIds->count();
  $bos    = $toplam - $dolu;
@endphp

<div class="max-w-6xl mx-auto space-y-6">

  {{-- HERO / Başlık ve Aksiyonlar --}}
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-700 to-violet-600 text-white shadow">
    <div class="p-6 md:p-8">
      <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
          <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-sm">
            <span class="opacity-90">Seans</span>
            <span class="font-semibold">#{{ $seans->id }}</span>
          </div>
          <h1 class="mt-3 text-2xl md:text-3xl font-bold tracking-tight">{{ $seans->film->title }}</h1>

          <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-white/90">
            <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1">
              Salon: <strong class="ml-1">{{ $seans->salon->Salon_adi }}</strong>
            </span>
            <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1">
              Başlangıç: <strong class="ml-1">{{ $seans->baslama_zamani->format('d.m.Y H:i') }}</strong>
            </span>
            <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1">
              Bitiş: <strong class="ml-1">{{ $seans->bitis_zamani->format('d.m.Y H:i') }}</strong>
            </span>
            <span class="inline-flex items-center rounded-full bg-emerald-400/90 text-black px-3 py-1 font-semibold">
              Dolu: {{ $dolu }} / {{ $toplam }}
            </span>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <a href="{{ route('admin.seans.index') }}"
             class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2 hover:bg-white/25 transition">
            ← Geri
          </a>
          <form action="{{ route('admin.seans.destroy', $seans) }}" method="POST"
                onsubmit="return confirm('Seansı iptal etmek istediğinize emin misiniz?');">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-red-500 px-4 py-2 font-semibold hover:bg-red-600 transition">
              İptal Et
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Seans Bilgileri (özet kartı) --}}
  <div class="bg-white rounded-2xl shadow p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Seans Bilgileri</h2>
    <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-4">
      <div>
        <dt class="text-sm text-gray-500">Film</dt>
        <dd class="text-gray-900 font-medium">{{ $seans->film->title }}</dd>
      </div>
      <div>
        <dt class="text-sm text-gray-500">Salon</dt>
        <dd class="text-gray-900 font-medium">{{ $seans->salon->Salon_adi }}</dd>
      </div>
      <div>
        <dt class="text-sm text-gray-500">Başlangıç</dt>
        <dd class="text-gray-900 font-medium">{{ $seans->baslama_zamani->format('d.m.Y H:i') }}</dd>
      </div>
      <div>
        <dt class="text-sm text-gray-500">Bitiş</dt>
        <dd class="text-gray-900 font-medium">{{ $seans->bitis_zamani->format('d.m.Y H:i') }}</dd>
      </div>
    </dl>
  </div>

  {{-- Koltuk Durumları --}}
  <div class="bg-white rounded-2xl shadow p-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-semibold text-gray-900">Koltuk Doluluk Durumu</h2>
      <div class="flex items-center gap-3 text-sm">
        <span class="inline-flex items-center gap-2">
          <span class="inline-block w-3 h-3 rounded bg-green-500 ring-1 ring-green-600/40"></span>
          <span class="text-gray-700">Boş ({{ $bos }})</span>
        </span>
        <span class="inline-flex items-center gap-2">
          <span class="inline-block w-3 h-3 rounded bg-red-500 ring-1 ring-red-600/40"></span>
          <span class="text-gray-700">Dolu ({{ $dolu }})</span>
        </span>
      </div>
    </div>

    {{-- Izgara --}}
    @if($toplam === 0)
      <div class="rounded-xl border border-gray-200 bg-gray-50 text-gray-600 p-6 text-center">
        Bu salonda aktif koltuk bulunmuyor.
      </div>
    @else
      <div class="grid grid-cols-3 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 gap-3">
        @foreach($koltuklar as $koltuk)
          @php $taken = $doluKoltukIds->contains($koltuk->id); @endphp
          <button
            type="button"
            class="seat-btn p-3 text-center rounded-xl font-semibold text-sm shadow-sm border
              {{ $taken ? 'bg-red-500 text-white border-red-600 hover:bg-red-600' : 'bg-green-500 text-white border-green-600 hover:bg-green-600' }}"
            data-booked="{{ $taken ? '1' : '0' }}"
            data-url="{{ route('admin.seans.seat.toggle', [$seans, $koltuk]) }}"
          >
            <div>{{ $koltuk->koltuk_no }}</div>
            <div class="text-[11px] opacity-90">{{ $taken ? 'DOLU' : 'BOŞ' }}</div>
          </button>
        @endforeach
      </div>

      {{-- Not: yönetici için ipucu --}}
      <p class="text-xs text-gray-500 mt-3">* Bir koltuğa tıklayarak durumunu hızlıca değiştirin.</p>
    @endif
  </div>

</div>
@endsection

@push('scripts')
<script>
  (function () {
    const token = @json(csrf_token());

    const toggleSeat = async (btn) => {
      const url    = btn.dataset.url;
      const booked = btn.dataset.booked === '1';

      // Onay sadece dolu ise
      if (booked) {
        const ok = confirm('Bu koltuk şu anda DOLU. Pasife almak istediğinize emin misiniz?');
        if (!ok) return;
      }

      // Optimistic UI: rengi geçici değiştir
      const prevClasses = btn.className;
      const prevBooked  = btn.dataset.booked;
      const prevLabel   = btn.querySelector('div:last-child')?.textContent;

      const setState = (isBooked) => {
        btn.dataset.booked = isBooked ? '1' : '0';
        btn.className = prevClasses
          .replace(/bg-(red|green)-\d{3}/g, '')
          .replace(/border-(red|green)-\d{3}/g, '')
          .replace(/hover:bg-(red|green)-\d{3}/g, '')
          .trim()
          + ' ' + (isBooked
              ? 'bg-red-500 border-red-600 hover:bg-red-600 text-white'
              : 'bg-green-500 border-green-600 hover:bg-green-600 text-white');
        const labelEl = btn.querySelector('div:last-child');
        if (labelEl) labelEl.textContent = isBooked ? 'DOLU' : 'BOŞ';
      };

      // geçici durum göster
      setState(!booked);

      try {
        const resp = await fetch(url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
          },
        });

        if (!resp.ok) {
          // sunucu hata → geri al
          setState(booked);
          const text = await resp.text();
          alert('İşlem başarısız: ' + (text || resp.status));
        } else {
          // Başarılı → sayfa üstündeki sayıları güncellemek istersen burada yeniden hesaplayabilirsin
        }
      } catch (e) {
        // ağ hatası → geri al
        setState(booked);
        console.error(e);
        alert('Ağ hatası: işlem gerçekleştirilemedi.');
      }
    };

    document.querySelectorAll('.seat-btn').forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        toggleSeat(btn);
      });
    });
  })();
</script>
@endpush
