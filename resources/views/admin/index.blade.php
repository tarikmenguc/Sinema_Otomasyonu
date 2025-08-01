@extends('admin.layouts.app')

@section('content')
  <div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-8">Yönetici Paneli</h1>
    
    {{-- Genel Bakış Kartları --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
      <div class="bg-white shadow rounded p-6">
        <h2 class="text-lg font-medium mb-2">Toplam Film</h2>
        <p class="text-4xl font-bold">{{ \App\Models\Film::count() }}</p>
        <a href="{{ route('admin.films.index') }}" class="text-blue-600 hover:underline mt-2 block">Tüm Filmler</a>
      </div>

      <div class="bg-white shadow rounded p-6">
        <h2 class="text-lg font-medium mb-2">Toplam Salon</h2>
        <p class="text-4xl font-bold">{{ \App\Models\Salon::count() }}</p>
        <a href="{{ route('admin.salon.index') }}" class="text-blue-600 hover:underline mt-2 block">Tüm Salonlar</a>
      </div>

      <div class="bg-white shadow rounded p-6">
        <h2 class="text-lg font-medium mb-2">Aktif Seanslar</h2>
        <p class="text-4xl font-bold">
          {{ \App\Models\Seans::where('is_active', true)
               ->where('bitis_zamani', '>', now())
               ->count() }}
        </p>
        <a href="{{ route('admin.seans.index') }}" class="text-blue-600 hover:underline mt-2 block">Seansları Gör</a>
      </div>

      <div class="bg-white shadow rounded p-6">
        <h2 class="text-lg font-medium mb-2">Toplam Bilet</h2>
        <p class="text-4xl font-bold">{{ \App\Models\Bilet::count() }}</p>
        <a href="{{ route('admin.bilets.index') }}" class="text-blue-600 hover:underline mt-2 block">Bilet Yönetimi</a>
      </div>
    </div>

    {{-- Hızlı Erişim Butonları --}}
    <div class="mb-12">
      <h2 class="text-2xl font-semibold mb-4">Hızlı Erişim</h2>
      <div class="flex flex-wrap gap-4">
        <a href="{{ route('admin.films.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Filmler</a>
        <a href="{{ route('admin.salon.index') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Salonlar</a>
        <a href="{{ route('admin.seans.index') }}" class="px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600">Seanslar</a>
        <a href="{{ route('admin.bilets.index') }}" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Biletler</a>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      {{-- Son Eklenen Filmler --}}
      <div>
        <h3 class="text-xl font-semibold mb-3">Son Eklenen Filmler</h3>
        <ul class="list-disc list-inside space-y-1">
          @foreach(\App\Models\Film::latest()->take(5)->get() as $film)
            <li>
              <a href="{{ route('admin.films.show', $film) }}" class="hover:underline">
                {{ $film->title }} ({{ $film->year }})
              </a>
            </li>
          @endforeach
        </ul>
      </div>

      {{-- Yaklaşan Seanslar (24 Saat) --}}
      <div>
        <h3 class="text-xl font-semibold mb-3">Yaklaşan Seanslar (24 Saat)</h3>
        <ul class="list-disc list-inside space-y-1">
          @foreach(\App\Models\Seans::where('baslama_zamani','>=', now())
                                      ->where('baslama_zamani','<', now()->addDay())
                                      ->with(['film','salon'])
                                      ->orderBy('baslama_zamani')
                                      ->get() as $s)
            <li>
              {{ $s->film->title }} —
              {{ $s->salon->Salon_adi }}:
              {{ $s->baslama_zamani->format('d.m.Y H:i') }}
            </li>
          @endforeach
        </ul>
      </div>

     
      <div>
        <h3 class="text-xl font-semibold mb-3">Son Satılan Biletler</h3>
        <ul class="list-disc list-inside space-y-1">
          @foreach(\App\Models\Bilet::latest()->take(5)->with(['user','seans.film'])->get() as $bilet)
            <li>
              {{ $bilet->user->name }} —
              {{ $bilet->seans->film->title }} /
              {{ $bilet->seans->baslama_zamani->format('d.m.Y H:i') }}
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
@endsection
