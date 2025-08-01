{{-- resources/views/admin/bilets/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Bilet Yönetimi')

@section('content')
<div class="space-y-6">

  {{-- Başlık --}}
  <h1 class="text-2xl font-bold">Seanslar ve Satılan Bilet Sayısı</h1>

  {{-- Tablo --}}
  <div class="overflow-x-auto bg-white shadow rounded">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Film</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Başlangıç</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bitiş</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Satılan Bilet</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">İşlemler</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @foreach($seanslar as $seans)
          <tr>
            <td class="px-4 py-2 text-sm">{{ $seans->id }}</td>
            <td class="px-4 py-2 text-sm">{{ $seans->film->title }}</td>
            <td class="px-4 py-2 text-sm">{{ $seans->baslama_zamani->format('d.m.Y H:i') }}</td>
            <td class="px-4 py-2 text-sm">{{ $seans->bitis_zamani->format('d.m.Y H:i') }}</td>
            <td class="px-4 py-2 text-sm">{{ $seans->bilets_count }}</td>
            <td class="px-4 py-2 text-sm">
              <a href="{{ route('admin.bilets.showbySeans', $seans) }}"
                 class="inline-block px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                Biletleri Gör
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</div>
@endsection
