{{-- resources/views/admin/seans/create.blade.php --}}
@extends('admin.layouts.app')
@section('title','Yeni Seans Oluştur')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

  {{-- FLASH --}}
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
    <div class="p-6 md:p-8 flex items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight">Yeni Seans Oluştur</h1>
        <p class="mt-1 text-white/90 text-sm">Film, salon ve başlangıç zamanını seçerek yeni bir seans ekleyin.</p>
      </div>
      <a href="{{ route('admin.seans.index') }}"
         class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2 hover:bg-white/25 transition">
        ← Geri
      </a>
    </div>
  </div>

  {{-- FORM KARTI --}}
  <form action="{{ route('admin.seans.store') }}" method="POST" class="bg-white rounded-2xl shadow p-6 space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      {{-- Film --}}
      <div>
        <label for="film_id" class="block text-sm font-medium text-gray-700 mb-1">Film</label>
        <select
          name="film_id" id="film_id" required
          class="block w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @error('film_id') border-red-500 @enderror"
        >
          <option value="">Seçiniz</option>
          @foreach($filmler as $id => $title)
            <option value="{{ $id }}" {{ old('film_id')==$id ? 'selected' : '' }}>{{ $title }}</option>
          @endforeach
        </select>
        @error('film_id')
          <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-xs text-gray-500">* Film süresi otomatik hesaplamaya dahil edilir (kayıtlıysa).</p>
      </div>

      {{-- Salon --}}
      <div>
        <label for="salon_id" class="block text-sm font-medium text-gray-700 mb-1">Salon</label>
        <select
          name="salon_id" id="salon_id" required
          class="block w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @error('salon_id') border-red-500 @enderror"
        >
          <option value="">Seçiniz</option>
          @foreach($salonlar as $id => $name)
            <option value="{{ $id }}" {{ old('salon_id')==$id ? 'selected' : '' }}>{{ $name }}</option>
          @endforeach
        </select>
        @error('salon_id')
          <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-xs text-gray-500">* Aktif salonlarda seans çakışmalarını kontrol etmeyi unutmayın.</p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      {{-- Başlangıç --}}
      <div>
        <label for="baslama_zamani" class="block text-sm font-medium text-gray-700 mb-1">Başlangıç Zamanı</label>
        <input
          type="datetime-local" name="baslama_zamani" id="baslama_zamani"
          value="{{ old('baslama_zamani') }}"
          min="{{ now()->format('Y-m-d\TH:i') }}"
          step="300"
          required
          class="block w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @error('baslama_zamani') border-red-500 @enderror"
        >
        @error('baslama_zamani')
          <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-xs text-gray-500">* En erken {{ now()->format('d.m.Y H:i') }} sonrası tarih seçilebilir.</p>
      </div>

      {{-- (Opsiyonel) Bitiş Tahmini --}}
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Bitiş (tahmini)</label>
        <input type="text" id="bitis_tahmini" readonly
               class="block w-full rounded-xl border-gray-200 bg-gray-50 text-gray-700">
        <p class="mt-1 text-xs text-gray-500">* Film süresine göre otomatik hesaplanır (yalnızca bilgi amaçlı).</p>
      </div>
    </div>

    {{-- BUTONLAR --}}
    <div class="flex items-center gap-2 pt-2">
      <button type="submit"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
        Kaydet
      </button>
      <a href="{{ route('admin.seans.index') }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-200 text-gray-800 hover:bg-gray-300 transition">
        İptal
      </a>
    </div>
  </form>

  {{-- İPUCU KARTI --}}
  <div class="bg-white rounded-2xl shadow p-6">
    <h3 class="text-base font-semibold text-gray-900 mb-2">İpuçları</h3>
    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
      <li>Bir film için arka arkaya seans eklerken, salonun bitiş/başlangıç tampon süresini düşünün.</li>
      <li>Seans liste sayfasından hızlıca iptal edebilir veya detaylarını görüntüleyebilirsiniz.</li>
    </ul>
  </div>

</div>
@endsection

@push('scripts')
<script>
  // Basit "bitiş tahmini" gösterimi (server tarafında zaten kesin hesap yapılıyor)
  // Runtime bilgisi backende kayıtlıysa Controller @store içinde hesaplayacaksın.
  // Burada sadece kullanıcıya görsel bir fikir veriyoruz (film süresini bilmiyorsak boş bırakır).
  (function() {
    const basInput = document.getElementById('baslama_zamani');
    const bitisOut = document.getElementById('bitis_tahmini');

    // Eğer film süresini option data-* ile geçmek istersen:
    // <option value="id" data-runtime="125">Film</option>
    // ve buradan okuyabilirsin; şimdilik varsayılan 120 dk (sadece öngörü)
    const defaultMinutes = 120;

    function fmt(dt) {
      if (!dt) return '';
      const pad = (n) => String(n).padStart(2, '0');
      return `${pad(dt.getDate())}.${pad(dt.getMonth()+1)}.${dt.getFullYear()} ${pad(dt.getHours())}:${pad(dt.getMinutes())}`;
    }

    function update() {
      const val = basInput.value;
      if (!val) { bitisOut.value = ''; return; }
      const start = new Date(val);
      if (isNaN(start.getTime())) { bitisOut.value = ''; return; }

      // Varsayılan 120 dk ekle (bilgi amaçlı)
      const end = new Date(start.getTime() + defaultMinutes * 60000);
      bitisOut.value = fmt(end);
    }

    basInput?.addEventListener('change', update);
    update();
  })();
</script>
@endpush
