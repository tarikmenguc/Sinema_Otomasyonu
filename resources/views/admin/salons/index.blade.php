{{-- resources/views/admin/salons/index.blade.php --}}
@extends('admin.layouts.app')
@section('title','Salonlar')

@section('content')
<div class="space-y-6">

  {{-- Başlık --}}
  <h1 class="text-2xl font-bold">Salonlar</h1>

  {{-- Tablo --}}
  <div class="overflow-x-auto bg-white shadow rounded">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Salon Adı</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kapasite</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Durum</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">İşlemler</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @foreach($salonlar as $salon)
          <tr>
            <td class="px-4 py-2">{{ $salon->id }}</td>
            <td class="px-4 py-2">{{ $salon->Salon_adi }}</td>
            <td class="px-4 py-2">{{ $salon->kapasite }}</td>
            <td class="px-4 py-2">
              @if($salon->aktifmi)
                <span class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded">Aktif</span>
              @else
                <span class="inline-block px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded">Pasif</span>
              @endif
            </td>
            <td class="px-4 py-2 space-x-2">
              <a href="{{ route('admin.salon.show', $salon) }}"
                 class="px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                Detay
              </a>
              <form action="{{ route('admin.salon.toggle', $salon) }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                        class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                  @if($salon->aktifmi) Pasifleştir @else Aktifleştir @endif
                </button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</div>
@endsection
