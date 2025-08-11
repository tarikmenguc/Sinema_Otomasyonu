{{-- resources/views/admin/seans/index.blade.php --}}
@extends('admin.layouts.app')
@section('title','Seanslar')

@section('content')
<div class="space-y-6">

  {{-- Başlık ve Buton --}}
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-800">🎬 Aktif Seanslar</h1>
    <a href="{{ route('admin.seans.create') }}"
       class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded shadow hover:bg-green-700 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
           stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
      </svg>
      Yeni Seans
    </a>
  </div>

  {{-- Tablo --}}
  <div class="overflow-x-auto bg-white shadow rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Film</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Salon</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Başlangıç</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bitiş</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">İşlemler</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-100">
        @forelse($seanslar as $seans)
          <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-2 text-gray-700 font-medium">{{ $seans->id }}</td>
            <td class="px-4 py-2 font-semibold text-gray-800">{{ $seans->film->title }}</td>
            <td class="px-4 py-2 text-gray-700">{{ $seans->salon->Salon_adi }}</td>
            <td class="px-4 py-2 text-blue-600 font-medium">{{ $seans->baslama_zamani->format('d.m.Y H:i') }}</td>
            <td class="px-4 py-2 text-red-500 font-medium">{{ $seans->bitis_zamani->format('d.m.Y H:i') }}</td>
            <td class="px-4 py-2 space-x-2">
              <a href="{{ route('admin.seans.show', $seans) }}"
                 class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition">
                🔍 Detay
              </a>
              <form action="{{ route('admin.seans.destroy', $seans) }}"
                    method="POST"
                    class="inline"
                    onsubmit="return confirm('Seansı iptal etmek istediğinize emin misiniz?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 transition">
                  ❌ İptal Et
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-4 py-6 text-center text-gray-500">
              📭 Hiç aktif seans bulunamadı.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>
@endsection
