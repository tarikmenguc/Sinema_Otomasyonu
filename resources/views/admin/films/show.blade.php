{{-- resources/views/admin/films/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', $film->title . ' | Film Detayı')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
    {{-- Başlık ve Butonlar --}}
    <div class="flex items-center justify-between px-6 py-4 bg-gray-100">
        <h1 class="text-2xl font-semibold text-gray-800">{{ $film->title }}</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.films.index') }}"
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                ← Geri
            </a>
            <form
                action="{{ route('admin.films.destroy', $film) }}"
                method="POST"
                class="inline"
                onsubmit="return confirm('Bu filmi silmek istediğinize emin misiniz?');"
            >
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 focus:outline-none">
                    Sil
                </button>
            </form>
        </div>
    </div>

    <div class="md:flex">
        {{-- Poster --}}
        @if($film->poster)
        <div class="md:w-1/3">
            <img
                src="{{ $film->poster }}"
                alt="Poster: {{ $film->title }}"
                class="object-cover w-full h-full"
            >
        </div>
        @endif

        {{-- Detaylar --}}
        <div class="md:w-2/3 p-6 space-y-4">
            <table class="w-full text-left">
                <tbody class="divide-y divide-gray-200">
                    <tr class="py-2">
                        <th class="py-2 font-medium text-gray-700">Yıl</th>
                        <td class="py-2 text-gray-800">{{ $film->year }}</td>
                    </tr>
                    <tr class="py-2">
                        <th class="py-2 font-medium text-gray-700">Çıkış Tarihi</th>
                        <td class="py-2 text-gray-800">{{ $film->released }}</td>
                    </tr>
                    <tr class="py-2">
                        <th class="py-2 font-medium text-gray-700">Süre</th>
                        <td class="py-2 text-gray-800">{{ $film->runtime }}</td>
                    </tr>
                    <tr class="py-2">
                        <th class="py-2 font-medium text-gray-700">Tür</th>
                        <td class="py-2 text-gray-800">{{ $film->genre }}</td>
                    </tr>
                    <tr class="py-2">
                        <th class="py-2 font-medium text-gray-700">Yönetmen</th>
                        <td class="py-2 text-gray-800">{{ $film->director }}</td>
                    </tr>
                    <tr class="py-2">
                        <th class="py-2 font-medium text-gray-700">Oyuncular</th>
                        <td class="py-2 text-gray-800">{{ $film->actors }}</td>
                    </tr>
                    <tr class="py-2">
                        <th class="py-2 font-medium text-gray-700">IMDb Puanı</th>
                        <td class="py-2 text-gray-800">{{ $film->imdb_rating }}</td>
                    </tr>
                </tbody>
            </table>

            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Özet</h2>
                <p class="text-gray-700 leading-relaxed">{{ $film->plot }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
