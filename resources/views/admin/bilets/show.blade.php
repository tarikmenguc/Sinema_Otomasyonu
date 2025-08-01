@extends('admin.layouts.app')

@section('title', "Seans #{$seans->id} Biletleri")

@section('content')
  <div class="space-y-6">
    <h1 class="text-2xl font-bold">Seans #{{ $seans->id }} Biletleri</h1>

    <a href="{{ route('admin.bilets.index') }}"
       class="inline-block px-4 py-2 bg-gray-500 text-white rounded">
      ← Geri
    </a>

    @if($biletler->isEmpty())
      <p class="text-gray-700">Bu seans için henüz bilet kesilmemiş.</p>
    @else
      <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th>#</th><th>Kullanıcı</th><th>Koltuk</th>
              <th>Kesilme Zamanı</th><th>Durum</th><th>İşlemler</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($biletler as $bilet)
              <tr>
                <td>{{ $bilet->id }}</td>
                <td>
                  {{ $bilet->user->name }}<br>
                  <span class="text-xs text-gray-500">{{ $bilet->user->email }}</span>
                </td>
                <td>{{ $bilet->koltuk->koltuk_no }}</td>
                <td>{{ $bilet->created_at->format('d.m.Y H:i') }}</td>
                <td>
                  @if($bilet->is_active)
                    <span class="px-2 py-1 text-xs text-green-800 bg-green-200 rounded">Aktif</span>
                  @else
                    <span class="px-2 py-1 text-xs text-gray-800 bg-gray-200 rounded">Pasif</span>
                  @endif
                </td>
                <td>
                  <form action="{{ route('admin.bilets.toggle', $bilet) }}"
                        method="POST"
                        onsubmit="return confirm('Durumunu değiştirmek istediğinize emin misiniz?')">
                    @csrf
                    @method('PATCH')
                    <button class="px-2 py-1 bg-yellow-500 text-white text-xs rounded">
                      @if($bilet->is_active) Pasif Yap @else Aktif Yap @endif
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
@endsection
