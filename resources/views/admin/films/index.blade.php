
@extends('admin.layouts.app')
@section('title','Filmler')

@section('content')
  <div class="space-y-6">

    {{-- Başlık --}}
    <h1 class="text-2xl font-bold">Filmler</h1>

    {{-- Arama formu --}}
    <form method="GET" action="{{ route('admin.films.index') }}" class="flex space-x-2">
      <input
        type="text"
        name="title"
        value="{{ request('title') }}"
        class="flex-1 px-3 py-2 border rounded shadow-sm focus:outline-none focus:ring"
        placeholder="Film başlığı ara..."
      >
      <button
        type="submit"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
      >
        Ara
      </button>
      <a
        href="{{ route('admin.films.index') }}"
        class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500"
      >
        Temizle
      </a>
    </form>

    {{-- Tablo --}}
    <div class="overflow-x-auto bg-white shadow rounded">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Başlık</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Yıl</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">IMDb ID</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">İşlemler</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @foreach($films as $film)
            <tr>
              <td class="px-4 py-2">{{ $film->id }}</td>
              <td class="px-4 py-2">
                <a href="{{ route('admin.films.show',$film) }}" class="text-blue-600 hover:underline">
                  {{ $film->title }}
                </a>
              </td>
              <td class="px-4 py-2">{{ $film->year }}</td>
              <td class="px-4 py-2">{{ $film->imdb_id }}</td>
              <td class="px-4 py-2 space-x-2">
                <form
                  action="{{ route('admin.films.destroy',$film) }}"
                  method="POST"
                  onsubmit="return confirm('Bu filmi silmek istediğinize emin misiniz?');"
                  class="inline"
                >
                  @csrf @method('DELETE')
                  <button class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                    Sil
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
      {{ $films->links() }}
    </div>

  </div>
@endsection
