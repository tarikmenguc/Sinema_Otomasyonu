{{-- resources/views/admin/seans/create.blade.php --}}
@extends('admin.layouts.app')
@section('title','Yeni Seans Oluştur')

@section('content')
<div class="space-y-6">

  {{-- Başlık --}}
  <h1 class="text-2xl font-bold">Yeni Seans Oluştur</h1>

  {{-- Form --}}
  <form action="{{ route('admin.seans.store') }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
    @csrf

    {{-- Film Seçimi --}}
    <div>
      <label for="film_id" class="block text-sm font-medium text-gray-700 mb-1">Film</label>
      <select
        name="film_id"
        id="film_id"
        class="block w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('film_id') border-red-500 @enderror"
      >
        <option value="">Seçiniz</option>
        @foreach($filmler as $id => $title)
          <option value="{{ $id }}" {{ old('film_id')==$id ? 'selected' : '' }}>
            {{ $title }}
          </option>
        @endforeach
      </select>
      @error('film_id')
        <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
      @enderror
    </div>

    {{-- Salon Seçimi --}}
    <div>
      <label for="salon_id" class="block text-sm font-medium text-gray-700 mb-1">Salon</label>
      <select
        name="salon_id"
        id="salon_id"
        class="block w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('salon_id') border-red-500 @enderror"
      >
        <option value="">Seçiniz</option>
        @foreach($salonlar as $id => $name)
          <option value="{{ $id }}" {{ old('salon_id')==$id ? 'selected' : '' }}>
            {{ $name }}
          </option>
        @endforeach
      </select>
      @error('salon_id')
        <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
      @enderror
    </div>

    {{-- Başlangıç Zamanı --}}
    <div>
      <label for="baslama_zamani" class="block text-sm font-medium text-gray-700 mb-1">Başlangıç Zamanı</label>
      <input
        type="datetime-local"
        name="baslama_zamani"
        id="baslama_zamani"
        value="{{ old('baslama_zamani') }}"
        class="block w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('baslama_zamani') border-red-500 @enderror"
      >
      @error('baslama_zamani')
        <p class="mt-1 text-red-600 text-sm">{{ $message }}</p>
      @enderror
    </div>

    {{-- Butonlar --}}
    <div class="flex space-x-2 pt-4">
      <button
        type="submit"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
      >
        Kaydet
      </button>
      <a
        href="{{ route('admin.seans.index') }}"
        class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500"
      >
        İptal
      </a>
    </div>

  </form>
</div>
@endsection
