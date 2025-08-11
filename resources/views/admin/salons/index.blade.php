{{-- resources/views/admin/salons/index.blade.php --}}
@extends('admin.layouts.app')
@section('title','Salonlar')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">

  {{-- Üst çubuk: başlık + aksiyonlar --}}
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Salonlar</h1>
      @php
        $aktifSay = $salonlar->where('aktifmi', true)->count();
        $toplam   = $salonlar->count();
      @endphp
      <p class="text-sm text-gray-500 mt-1">{{ $aktifSay }} aktif / {{ $toplam }} toplam</p>
    </div>

    <div class="flex gap-2">
      @if(Route::has('admin.salon.create'))
      <a href="{{ route('admin.salon.create') }}"
         class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-white text-sm hover:bg-indigo-700 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Yeni Salon
      </a>
      @endif
      <a href="{{ route('admin.salon.index') }}"
         class="inline-flex items-center rounded-xl border border-gray-200 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
        Yenile
      </a>
    </div>
  </div>

  {{-- Filtre / Arama --}}
  <form method="GET" action="{{ route('admin.salon.index') }}"
        class="bg-white rounded-2xl shadow p-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-4">
    <div class="relative flex-1">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Salon adı ile ara…"
             class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 pl-10">
      <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l3.387 3.387a1 1 0 01-1.414 1.414l-3.387-3.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd" /></svg>
    </div>

    <div>
      <select name="status" class="rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">Tüm Durumlar</option>
        <option value="1" @selected(request('status')==='1')>Aktif</option>
        <option value="0" @selected(request('status')==='0')>Pasif</option>
      </select>
    </div>

    <div class="flex gap-2">
      <button class="rounded-xl bg-gray-900 text-white px-4 py-2 text-sm hover:bg-black transition">
        Uygula
      </button>
      <a href="{{ route('admin.salon.index') }}"
         class="rounded-xl border border-gray-200 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
        Temizle
      </a>
    </div>
  </form>

  {{-- Bildirim (flash) --}}
  @if(session('status'))
    <div class="rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3">
      {{ session('status') }}
    </div>
  @endif

  {{-- Masaüstü: Tablo --}}
  <div class="hidden md:block overflow-hidden bg-white shadow rounded-2xl">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-gray-50 sticky top-0 z-10">
          <tr class="text-left text-xs font-medium text-gray-500 uppercase">
            <th class="px-5 py-3">#</th>
            <th class="px-5 py-3">Salon Adı</th>
            <th class="px-5 py-3">Kapasite</th>
            <th class="px-5 py-3">Durum</th>
            <th class="px-5 py-3 text-right">İşlemler</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($salonlar as $salon)
            <tr class="hover:bg-gray-50">
              <td class="px-5 py-3 text-gray-700">{{ $salon->id }}</td>
              <td class="px-5 py-3 font-semibold text-gray-900">{{ $salon->Salon_adi }}</td>
              <td class="px-5 py-3 text-gray-700">
                <span class="inline-flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6a2 2 0 012-2h12a2 2 0 012 2v9a2 2 0 01-2 2h-4l-2 3-2-3H6a2 2 0 01-2-2V6z"/></svg>
                  {{ $salon->kapasite }}
                </span>
              </td>
              <td class="px-5 py-3">
                @if($salon->aktifmi)
                  <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-800">Aktif</span>
                @else
                  <span class="inline-flex items-center rounded-full bg-gray-200 px-2.5 py-1 text-xs font-semibold text-gray-800">Pasif</span>
                @endif
              </td>
              <td class="px-5 py-3">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.salon.show', $salon) }}"
                     class="px-3 py-1.5 rounded-xl bg-indigo-600 text-white text-xs hover:bg-indigo-700 transition">
                    Detay
                  </a>
                  <form action="{{ route('admin.salon.toggle', $salon) }}" method="POST"
                        onsubmit="return confirm('Durumu değiştirmek istediğinize emin misiniz?');">
                    @csrf
                    <button type="submit"
                            class="px-3 py-1.5 rounded-xl bg-amber-500 text-white text-xs hover:bg-amber-600 transition">
                      {{ $salon->aktifmi ? 'Pasifleştir' : 'Aktifleştir' }}
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-5 py-10">
                <div class="text-center text-gray-500">
                  <p class="font-medium">Gösterilecek salon yok.</p>
                  @if(Route::has('admin.salon.create'))
                  <a href="{{ route('admin.salon.create') }}"
                     class="inline-flex mt-3 rounded-xl border border-gray-200 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                    Yeni salon ekle
                  </a>
                  @endif
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Mobil: Kart listesi --}}
  <div class="grid grid-cols-1 gap-4 md:hidden">
    @forelse($salonlar as $salon)
      <div class="bg-white rounded-2xl shadow p-4">
        <div class="flex items-start justify-between">
          <div>
            <h3 class="text-base font-semibold text-gray-900">{{ $salon->Salon_adi }}</h3>
            <p class="text-sm text-gray-500 mt-1">Kapasite: <span class="font-medium text-gray-800">{{ $salon->kapasite }}</span></p>
          </div>
          <div>
            @if($salon->aktifmi)
              <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-800">Aktif</span>
            @else
              <span class="inline-flex items-center rounded-full bg-gray-200 px-2.5 py-1 text-xs font-semibold text-gray-800">Pasif</span>
            @endif
          </div>
        </div>

        <div class="mt-4 flex items-center gap-2">
          <a href="{{ route('admin.salon.show', $salon) }}"
             class="flex-1 text-center rounded-xl bg-indigo-600 text-white text-xs px-3 py-2 hover:bg-indigo-700 transition">
            Detay
          </a>
          <form action="{{ route('admin.salon.toggle', $salon) }}" method="POST"
                class="flex-1"
                onsubmit="return confirm('Durumu değiştirmek istediğinize emin misiniz?');">
            @csrf
            <button type="submit"
                    class="w-full text-center rounded-xl bg-amber-500 text-white text-xs px-3 py-2 hover:bg-amber-600 transition">
              {{ $salon->aktifmi ? 'Pasifleştir' : 'Aktifleştir' }}
            </button>
          </form>
        </div>
      </div>
    @empty
      <div class="bg-white rounded-2xl shadow p-6 text-center text-gray-500">
        Gösterilecek salon yok.
      </div>
    @endforelse
  </div>

  {{-- (Varsa) sayfalama --}}
  @if(method_exists($salonlar, 'links'))
    <div class="pt-2">{{ $salonlar->appends(request()->query())->links() }}</div>
  @endif

</div>
@endsection
