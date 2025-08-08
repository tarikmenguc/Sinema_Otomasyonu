{{-- resources/views/admin/seans/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', "Seans Detayı #{$seans->id}")

@section('content')
<div class="space-y-6">

  {{-- Başlık ve Butonlar --}}
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Seans Detayı #{{ $seans->id }}</h1>
    <div class="space-x-2">
      <a href="{{ route('admin.seans.index') }}"
         class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
        ← Geri
      </a>
      <form action="{{ route('admin.seans.destroy', $seans) }}"
            method="POST"
            class="inline"
            onsubmit="return confirm('Seansı iptal etmek istediğinize emin misiniz?');">
        @csrf
        @method('DELETE')
        <button
          class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
          İptal Et
        </button>
      </form>
    </div>
  </div>

  {{-- Seans Bilgileri --}}
  <div class="overflow-x-auto bg-white shadow rounded p-6">
    <table class="min-w-full divide-y divide-gray-200">
      <tbody class="bg-white divide-y divide-gray-200">
        <tr>
          <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 uppercase">Film</th>
          <td class="px-4 py-2 text-sm">{{ $seans->film->title }}</td>
        </tr>
        <tr>
          <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 uppercase">Salon</th>
          <td class="px-4 py-2 text-sm">{{ $seans->salon->Salon_adi }}</td>
        </tr>
        <tr>
          <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 uppercase">Başlangıç</th>
          <td class="px-4 py-2 text-sm">{{ $seans->baslama_zamani->format('d.m.Y H:i') }}</td>
        </tr>
        <tr>
          <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 uppercase">Bitiş</th>
          <td class="px-4 py-2 text-sm">{{ $seans->bitis_zamani->format('d.m.Y H:i') }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- Koltuk Durumları --}}
  <div class="space-y-4">
    <h2 class="text-xl font-semibold">Koltuk Doluluk Durumu</h2>
    <div class="grid grid-cols-6 gap-4">
     @foreach($seans->salon->koltuks->where('is_active', true) as $koltuk)
        @php
          // Koltuk modelinizde:
          // public function biletForSeans($seansId) { ... }
          $taken = (bool) $koltuk->biletForSeans($seans->id);
        @endphp

        <button
          type="button"
          class="seat-btn p-3 text-center rounded {{ $taken ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}"
          data-booked="{{ $taken ? '1' : '0' }}"
          data-url="{{ route('admin.seans.seat.toggle', [$seans, $koltuk]) }}"
        >
          <div class="font-bold">{{ $koltuk->koltuk_no }}</div>
          <div class="text-xs">{{ $taken ? 'DOLU' : 'BOŞ' }}</div>
        </button>
      @endforeach
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
  document.querySelectorAll('.seat-btn').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();

      const booked = btn.dataset.booked === '1';
      const url    = btn.dataset.url;

    
      if (booked) {
        if (! confirm('Bu koltuk şu anda DOLU. Pasife almak istediğinize emin misiniz?')) {
          return;
        }
      }

      const form = document.createElement('form');
      form.method = 'POST';
      form.action = url;
      form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
      `;
      document.body.appendChild(form);
      form.submit();
    });
  });
</script>
@endpush
