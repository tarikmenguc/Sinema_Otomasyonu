{{-- resources/views/admin/salons/show.blade.php --}}
@extends('admin.layouts.app')
@section('title','Salon Detayı')

@section('content')
<div class="space-y-6">

  {{-- Başlık ve Geri Düğmesi --}}
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold">Salon Detayı: {{ $salon->Salon_adi }}</h1>
    <a
      href="{{ route('admin.salon.index') }}"
      class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600"
    >
      ← Geri
    </a>
  </div>

  {{-- Salon Bilgileri --}}
  <div class="bg-white shadow rounded p-6">
    <table class="min-w-full divide-y divide-gray-200">
      <tbody class="divide-y divide-gray-200">
        <tr class="py-2">
          <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ID</th>
          <td class="px-4 py-2">{{ $salon->id }}</td>
        </tr>
        <tr class="py-2">
          <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Salon Adı</th>
          <td class="px-4 py-2">{{ $salon->Salon_adi }}</td>
        </tr>
        <tr class="py-2">
          <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Kapasite</th>
          <td class="px-4 py-2">{{ $salon->kapasite }}</td>
        </tr>
        <tr class="py-2">
          <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Durum</th>
          <td class="px-4 py-2">
            @if($salon->aktifmi)
              <span class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded">Aktif</span>
            @else
              <span class="inline-block px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded">Pasif</span>
            @endif
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- Aktif Seanslar --}}
  <div class="bg-white shadow rounded p-6">
    <h2 class="text-xl font-semibold mb-4">Aktif Seanslar</h2>

    @if($salon->seanslar->isEmpty())
      <p class="text-gray-600">Bu salonda şu anda aktif bir seans yok.</p>
    @else
      <ul class="divide-y divide-gray-200">
        @foreach($salon->seanslar as $s)
          <li class="py-3 flex justify-between items-center">
            <div>
              <p class="font-medium">{{ $s->film->title }}</p>
              <p class="text-sm text-gray-500">
                {{ $s->baslama_zamani->format('d.m.Y H:i') }}
                &rarr;
                {{ $s->bitis_zamani->format('d.m.Y H:i') }}
              </p>
            </div>
            <a
              href="{{ route('admin.seans.show', $s) }}"
              class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700"
            >
              Görüntüle
            </a>
          </li>
        @endforeach
      </ul>
    @endif
  </div>

  {{-- Koltuk Listesi --}}
  <div class="bg-white shadow rounded p-6">
    <h2 class="text-xl font-semibold mb-4">Koltuk Listesi</h2>
    <div class="grid grid-cols-6 gap-4">
      @foreach($salon->koltuks as $k)
        <div class="p-4 border rounded text-center text-sm font-medium">
          {{ $k->koltuk_no }}
        </div>
      @endforeach
    </div>
  </div>

</div>
@endsection
