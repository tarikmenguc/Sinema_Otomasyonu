{{-- resources/views/admin/seans/index.blade.php --}}
@extends('admin.layouts.app')
@section('title','Seanslar')

@section('content')
<div class="space-y-6">

  {{-- Başlık --}}
  <h1 class="text-2xl font-bold">Aktif Seanslar</h1>

  {{-- Yeni Seans Butonu --}}
  <a href="{{ route('admin.seans.create') }}"
     class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
    Yeni Seans Oluştur
  </a>

  {{-- Tablo --}}
  <div class="overflow-x-auto bg-white shadow rounded">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Film</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Salon</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Başlangıç</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bitiş</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">İşlemler</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @forelse($seanslar as $seans)
          <tr>
            <td class="px-4 py-2">{{ $seans->id }}</td>
            <td class="px-4 py-2">{{ $seans->film->title }}</td>
            <td class="px-4 py-2">{{ $seans->salon->Salon_adi }}</td>
            <td class="px-4 py-2">{{ $seans->baslama_zamani->format('d.m.Y H:i') }}</td>
            <td class="px-4 py-2">{{ $seans->bitis_zamani->format('d.m.Y H:i') }}</td>
            <td class="px-4 py-2 space-x-2">
              <a href="{{ route('admin.seans.show', $seans) }}"
                 class="px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                Detay
              </a>
              <form action="{{ route('admin.seans.destroy', $seans) }}"
                    method="POST"
                    class="inline"
                    onsubmit="return confirm('Seansı iptal etmek istediğinize emin misiniz?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                  İptal Et
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-4 py-2 text-center text-gray-500">
              Hiç aktif seans bulunamadı.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>
@endsection
